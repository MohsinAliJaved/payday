<?php

namespace App\Http\Controllers\Tenant\Applicant;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Tenant\JobPost\JobPost;
use App\Models\Tenant\Applicant\Applicant;
use App\Models\Tenant\Recruitment\JobStage;
use App\Models\Tenant\Applicant\JobApplicant;
use App\Models\Tenant\Recruitment\HiringTeam;
use App\Helpers\Tenant\AppOnDeleteRelatedModels;
use App\Filters\Tenant\Applicant\ApplicantFilter;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\Applicant\ApplicantService;
use App\Http\Requests\Tenant\Applicant\ApplicantRequest;

class ApplicantController extends Controller
{
    public function __construct(ApplicantService $service, ApplicantFilter $applicantFilter)
    {
        $this->service = $service;
        $this->filter = $applicantFilter;
    }

    public function index()
    {
        return $this->service
            ->with(
                [
                    'totalApplication',
                    'jobApplicants' => function ($q) {
                        $this->filter
                            ->appliedDate($q)
                            ->applicantJobPost($q)
                            ->jobApplicantStatus($q)
                            ->jobApplicantReview($q);
                    },
                    'jobApplicants.jobPost',
                    'jobApplicants.currentStage',
                    'jobApplicants.status',
                ]
            )
            ->whereHas('jobApplicants', function ($query) {
                $query->when(! request()->get('status'), function ($query) {
                    $query->where('status_id', '!=', resolve(StatusRepository::class)
                        ->getStatusId('job_applicant', 'status_disqualified'));
                }, function ($query) {
                    $id = request()->get('status');
                    $query->where('status_id', intval($id));
                });

                if (auth()->user()->can('can_view_applicant')) {
                    if (! auth()->user()->isAppAdmin()) {
                        $user_id = auth()->user()->id;
                        $jobs = HiringTeam::where('recruiter_id', $user_id)->pluck('job_post_id')->toArray();
                        $query->whereIn('job_post_id', $jobs);
                    } else {
                        $query->whereRaw('true');
                    }
                } else {
                    $query->whereRaw('false');
                }

                $query->when(request()->get('job'), function ($query) {
                    $id = request()->get('job');
                    $query->where('job_post_id', intval($id));
                });

                $query->when(request()->get('review'), function ($query) {
                    $query->where('review', request()->get('review'));
                });

                $query->when(request()->get('applied_date_range'), function ($query) {
                    $date = json_decode(htmlspecialchars_decode(request()->get('applied_date_range')), true);
                    $query->when($date, function ($q) use ($date) {
                        $q->whereBetween(\DB::raw('DATE(created_at)'), array_values($date));
                    });
                });
            })
            ->when(request()->get('gender'), function ($query) {
                $query->where('gender', request()->get('gender'));
            })
            ->when(request()->get('search'), function ($query) {
                $value = request()->get('search');
                $query->whereRaw("CONCAT(first_name,' ',last_name) LIKE ?", "%{$value}%")
                    ->orWhere('email', $value);
            })
            ->when(request()->get('search'), function ($query) {
                $query->orderBy(DB::raw("CONCAT(first_name,' ',last_name)"));
            }, function ($query) {
                $query->latest();
            })
            ->paginate(request()->get('per_page', 10));
    }

    public function store(ApplicantRequest $request): array
    {
        $result = $this->service
            ->setAttributes($request->only(
                'first_name',
                'last_name',
                'email',
                'gender',
                'date_of_birth'
            ))->save();

        $stage = JobStage::query()->where('name', 'new')->where('job_post_id', $request->job_post_id)->first();

        if ($result) {
            $apply_form_setting = JobPost::find($request->job_post_id);
            $jobApplicant = JobApplicant::query()->create([
                'applicant_id' => $result->id,
                'job_post_id' => $request->job_post_id,
                'current_stage_id' => $stage->id ?? null,
                'apply_form_setting' => $apply_form_setting->apply_form_settings,
                'status_id' => resolve(StatusRepository::class)->getStatusId('job_applicant', 'status_new'),
                'slug' => Str::uuid(),
            ]);

            //Store to timeline
            $description = trans('default.timeline_for_applied_job_by_system');
            $find = ['{candidate_name}', '{job_post_name}', '{user_name}'];
            $jobPost = JobPost::find($request->job_post_id);
            $replace = [$jobApplicant->appliedBy->full_name, $jobPost->name, auth()->user()->full_name];
            $description = str_replace($find, $replace, $description);
            log_to_database($description, [], 'timeline', auth()->user(), $jobApplicant);

            return created_responses('applicant');
        }

        return custom_failed_response('candidate_already_exist');
    }

    public function show(Applicant $applicant): object
    {
        return $this->service
            ->with(
                'jobApplicants',
                'jobApplicants.jobPost',
                'jobApplicants.currentStage',
                'jobApplicants.status'
            )->where('id', $applicant->id)->first();
    }

    public function update(ApplicantRequest $request, Applicant $applicant)
    {
        $this->service
            ->setModel($applicant)
            ->save(
                $request->only(
                    'first_name',
                    'last_name',
                    'email',
                    'gender',
                    'date_of_birth'
                )
            );

        return updated_responses('applicant');
    }

    public function destroy(Applicant $applicant, AppOnDeleteRelatedModels $model)
    {
        $model = $model->setModel($applicant);
        $data = $model->loadRelatedModelsOnDeleteApplicant();
        $model->removeData();

        $applicant->delete();

        return deleted_responses('applicant');
    }

    //-------------Aggregate Function -----------------------

    public function checkEmail(Request $request, Applicant $applicant)
    {
        $request->validate([
            'email' => 'required | email',
        ]);

        return $applicant->where('email', $request->email)->first();
    }

    //---------------------------------------------------------
}
