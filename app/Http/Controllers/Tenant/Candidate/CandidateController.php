<?php

namespace App\Http\Controllers\Tenant\Candidate;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Core\Setting\Setting;
use App\Models\Tenant\JobPost\JobPost;
use App\Helpers\Core\Traits\FileHandler;
use App\Models\Tenant\Applicant\Applicant;
use App\Models\Tenant\Recruitment\JobStage;
use App\Models\Tenant\Applicant\JobApplicant;
use App\Models\Tenant\Applicant\ApplicationAnswer;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\Applicant\JobApplicantService;

class CandidateController extends Controller
{
    use FileHandler;

    public function showJobApplicantDetails(JobApplicant $applicant, $slug)
    {
        return $applicant
            ->select(
                'id',
                'applicant_id',
                'job_post_id',
                'current_stage_id',
                'status_id',
                'slug',
                'review',
                'created_at'
            )
            ->with([
                'status:id,name,type',
                'appliedBy:id,first_name,last_name,email',
                'jobPost:id,name,slug',
                'currentStage:id,name',
                'answers:id,question,answer,attachment',
            ])
            ->where('slug', $slug)->first();
    }

    public function showCareerPage(JobPost $jobPost)
    {
        $setting = Setting::where('name', 'career_page')->first();
        //$careerPage = array( $setting->name => json_decode($setting->value));
        $careerPage = json_decode($setting->value);
        $jobPosts = $jobPost
            ->select('id', 'name', 'job_type_id', 'slug', 'company_location_id', 'last_submission_date')
            ->with([
                'location:id,address',
                'jobType:id,name',
            ])
            ->where('status_id', resolve(StatusRepository::class)->getStatusId('job_post', 'status_open'))
            ->get();

        return view('candidates.career-page', ['careerPage' => $careerPage, 'jobPosts' => $jobPosts]);
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'job_post_id' => 'required | exists:job_posts,id',
            'email_address' => 'required|email',
        ]);

        $applicant = Applicant::where('email', $request->email_address)->first();

        if (!$applicant) {
            return;
        }
        $jobAppli = JobApplicant::
        where('job_post_id', $request->job_post_id)
            ->where('applicant_id', $applicant->id)
            ->first();

        if ($jobAppli) {
            return custom_failed_response('already_applied_with_this_email');
        }

        return $applicant;
    }

    public function applyJobPost(Request $request, $slug)
    {
        $request->validate([
            'apply_form_setting' => 'required|string',
            'applicant_id' => 'nullable|exists:applicants,id',
            'question_answer' => 'nullable|array',
            'question_answer.*.question' => 'required|string',
            'question_answer.*.answer' => 'nullable|string',
            'question_answer.*.attachment' => 'nullable | file',
        ]);

        $applicant_id = $request->applicant_id;

        if (!isset($request->applicant_id) || empty($request->applicant_id)) {
            $request->validate([
                'basic_information' => 'required|array',
                'basic_information.first_name' => 'required|string',
                'basic_information.last_name' => 'required|string',
                'basic_information.email' => 'required|email|unique:applicants,email',
                'basic_information.gender' => ['required', Rule::in(['male', 'female', 'other'])],
                'basic_information.date_of_birth' => 'nullable|date|date_format:Y-m-d',
            ]);

            $applicant = $this->storeApplicant($request->basic_information);
            $applicant_id = $applicant->id;
        }

        $jobPost = JobPost::where('slug', $slug)->first();

        if (!$jobPost) {
            return custom_failed_response('invalid_job_post');
        }

        $jobApplicant = $this->assignJobToApplicant($applicant_id, $jobPost->id, $request->apply_form_setting);

        if (!$jobApplicant) {
            Applicant::destroy($applicant_id);

            return custom_failed_response('already_applied_with_this_email');
        }
        if(isset($request->question_answer) && $request->question_answer != null && count($request->question_answer) > 0) {
            $answers = $this->storeQuestionAnswers($jobApplicant->id, $request->question_answer);

            if (!$answers) {
                return custom_failed_response('failed_to_store_job_applicant');
            }
        }

        // Send notification and mail snippet
        if ($jobApplicant) {
            resolve(JobApplicantService::class)
                ->setModel($jobApplicant)
                ->setNotifyCandidateForJobApply(true)
                ->notify('job_applied', AppliedJobNotification::class);

            //Store to timeline
            $description = trans('default.timeline_for_applied_job');
            $find = ['{candidate_name}', '{job_post_name}'];
            $replace = [$jobApplicant->appliedBy->full_name, $jobPost->name];
            $description = str_replace($find, $replace, $description);
            log_to_database($description, [], 'timeline', null, $jobApplicant);
        }

        return custom_response('candidate_applied_successfully');
    }

    private function storeApplicant($basic_information)
    {
        return Applicant::create($basic_information);
    }

    private function assignJobToApplicant($applicantId, $jobPostId, $appliFormSetting)
    {
        $jobApplicant = JobApplicant::where('job_post_id', $jobPostId)
            ->where('applicant_id', $applicantId)->first();

        if ($jobApplicant) {
            return false;
        }

        $stage = JobStage::query()->where('name', 'new')->where('job_post_id', $jobPostId)->first();

        return JobApplicant::query()->create([
            'applicant_id' => $applicantId,
            'job_post_id' => $jobPostId,
            'current_stage_id' => $stage->id ?? null,
            'apply_form_setting' => $appliFormSetting,
            'status_id' => resolve(StatusRepository::class)->getStatusId('job_applicant', 'status_new'),
            'slug' => Str::uuid(),
        ]);
    }

    private function storeQuestionAnswers($jobApplicantId, $answers)
    {
        $record = [];
        foreach ($answers as $answer) {
            array_push($record, [
                'job_applicant_id' => $jobApplicantId,
                'question' => $answer['question'],
                'answer' => $answer['answer'],
                'attachment' => $this->storeEachFile($answer['attachment'] ?? ''),
            ]);
        }
        $answers = ApplicationAnswer::query()->insert($record);

        return $answers;
    }

    private function storeEachFile($file)
    {
        if ($file) {
            return $this->isWithOriginalName()->storeFile($file, 'attachments');
        }

        return '';
    }
}
