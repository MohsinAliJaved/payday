<?php

namespace App\Http\Controllers\Tenant\JobPost;

use App\Models\Core\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\JobPost\JobPost;
use App\Models\Tenant\Applicant\Applicant;
use App\Filters\Tenant\JobPost\JobPostFilter;
use App\Models\Tenant\Recruitment\HiringTeam;
use App\Services\Tenant\JobPost\JobPostService;
use App\Repositories\Core\Status\StatusRepository;

class AggregateJobController extends Controller
{
    public function __construct(JobPostService $service, JobPostFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    //--------------------AGGREGATE METHODS--------------------------------------------
    public function summery()
    {
        return $this->service
            ->filters($this->filter)
            ->select('id', 'company_location_id', 'posted_by', 'job_type_id', 'status_id', 'department_id', 'name', 'slug', 'created_at', 'stages', 'salary', 'last_submission_date')
            ->with([
                'location:id,address',
                'jobType:id,name',
                'department:id,name',
                'status:id,name,class,type',
                'postedBy:id,first_name,last_name,email',
                'jobStages' => function ($query) {
                    $query->select('id', 'name', 'job_post_id')
                        ->with(['jobApplicantCount']);
                },
                'totalApplicants',
            ])
            ->when(!auth()->user()->isAppAdmin(),
                function ($query) {
                    if (auth()->user()->can('can_view_job_post')) {
                        $jobs = HiringTeam::where('recruiter_id', auth()->user()->id)->pluck('job_post_id')->toArray();
                        $query->whereIn('id', $jobs);
                    } else {
                        $query->whereRaw('false');
                    }
                }
            )
            ->when(!request()->get('status'), function ($query) {
                $query->where('status_id', resolve(StatusRepository::class)->getStatusId('job_post', 'status_open'));
            })
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function viewOverview($jobPostId)
    {
        return view('tenant.job_post.job-overview', ['job_post_id' => $jobPostId]);
    }

    public function overview(JobPost $jobPost, $id)
    {
        return $jobPost
            ->select('id', 'company_location_id', 'posted_by', 'job_type_id', 'status_id', 'name', 'slug', 'created_at', 'stages')
            ->with([
                'location:id,address',
                'jobType:id,name',
                'status:id,name,class,type',
                'postedBy:id,first_name,last_name,email',
                'jobStages' => function ($query) {
                    $query->select('id', 'name', 'job_post_id')
                        ->with([
                            'jobApplicantCount',
                            'jobApplicants.appliedBy:id,first_name,last_name,email',
                            'jobApplicants.status:id,name,class,type'
                        ]);
                },

                'totalApplicants',
            ])
            ->when(!auth()->user()->isAppAdmin(),
                function ($query) {
                    if (auth()->user()->can('can_view_job_post')) {
                        $jobs = HiringTeam::where('recruiter_id', auth()->user()->id)->pluck('job_post_id')->toArray();
                        $query->whereIn('id', $jobs);
                    } else {
                        $query->whereRaw('false');
                    }
                }
            )
            ->find($id);
    }

    public function applicants(Applicant $applicant, $jobPostId)
    {
        if(intval($jobPostId) < 1){
            return [];
        }
        $jobPostId = intval($jobPostId);
        return $applicant
            ->with([
                    'jobApplicants' => function ($query) use ($jobPostId) {
                        $query->where('job_post_id', $jobPostId);
                    },
                    'jobApplicants.currentStage',
                    'jobApplicants.status',
                    'totalApplication'
                ]
            )
            ->whereHas('jobApplicants', function ($query) use ($jobPostId) {
                $query->where('job_post_id', intval($jobPostId));
            })
            ->when(!auth()->user()->isAppAdmin(),
                function ($query)  use($jobPostId){
                    if (auth()->user()->can('can_view_job_post')) {
                        $jobs = HiringTeam::where('recruiter_id', auth()->user()->id)->pluck('job_post_id')->toArray();
                        in_array($jobPostId, $jobs) ? $query->whereRaw('true') : $query->whereRaw('false');

                    } else {
                        $query->whereRaw('false');
                    }
                })
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function showJobPost(JobPost $jobPost, Status $status, $jobPostSlug)
    {
        $status = $status
            ->where('type', 'job_post')
            ->where('name', 'status_open')
            ->first();

        $job = $jobPost
            ->select('id', 'slug', 'name', 'description', 'job_type_id',
                'company_location_id', 'job_post_settings', 'status_id', 'last_submission_date')
            ->with([
                'jobPostThumbnail',
                'status:id,name,type',
                'jobType:id,name',
                'location:id,address',
                'totalApplicants'
            ])
            ->where('slug', $jobPostSlug)
            ->first();

        if ($job) {
            $response = $job;
            if(auth()->id() === null && ($job->last_submission_date < date("Y-m-d") || intval($job->status_id) != intval($status->id))){
                return view('custom_errors.404',['message' => __t('job_post_closed')]);
            }

        } else {
            return view('custom_errors.404',['message' => __t('no_job_post_found')]);
        }

        $applyRoute = route('public.jobPost.apply_job_post', ['job_slug' => $job->slug]);
        $viewRoute = route('public.jobPost.show_pob_post', ['job_slug' => $job->slug]);

        return view('candidates.job-post', ['response' => $response, 'applyLink' => $applyRoute, 'viewLink' => $viewRoute]);
    }

    public function applyJobPost(JobPost $jobPost, Status $status, $jobPostSlug)
    {
        $status = $status
            ->where('type', 'job_post')
            ->where('name', 'status_open')
            ->first();

        $job = $jobPost
            ->with('jobPostThumbnail')
            ->select('id', 'slug', 'name', 'description', 'job_type_id',
                'company_location_id', 'apply_form_settings', 'status_id', 'last_submission_date')
            ->where('slug', $jobPostSlug)
            ->first();

        $response = false;

        if ($job) {
            $response = $job;
            if(auth()->id() === null && ($job->last_submission_date < date("Y-m-d") || intval($job->status_id) != intval($status->id))){
                return view('custom_errors.404',['message' => __t('job_post_closed')]);
            }

        } else {
            return view('custom_errors.404',['message' => __t('no_job_post_found')]);
        }

        $viewRoute = route('public.jobPost.apply_job_post', ['job_slug' => $job->slug]);

        return view('candidates.apply-form', ['response' => $response, 'viewLink' => $viewRoute]);
    }

    public function viewSetting($jobPostId)
    {
        return view('tenant.job_post.job-settings', ['job_post_id' => $jobPostId]);
    }

    public function editJobPost(JobPost $job)
    {
        $previewLink = route('public.jobPost.show_pob_post', ['job_slug' => $job->slug]);
        return view('tenant.job_post.job-editor', ['job' => $job, 'previewLink' => $previewLink]);
    }

    public function updateJobPost(Request $request, JobPost $job)
    {
        $request->validate([
            'job_post_settings' => 'required|array'
        ]);

        $this->service
            ->setModel($job)
            ->save($request->only(['job_post_settings']));

        return $job->wasChanged() ? updated_responses('job_post_template') : failed_responses(['job_post_settings' => $request->job_post_settings]);

    }

    public function publishJobPost(Request $request, JobPost $job)
    {
        $request->validate([
            'job_post_settings' => 'required|array'
        ]);

        $input = [];
        $input['job_post_settings'] = $request->job_post_settings;
        $input['status_id'] = resolve(StatusRepository::class)->getStatusId('job_post', 'status_open');

        $this->service
            ->setModel($job)
            ->save($input);

        return $job->wasChanged() ? updated_responses('job_post_template') : failed_responses(['job_post_settings' => $request->job_post_settings]);
    }

    public function editApplyForm(Request $request, JobPost $job)
    {
        $request->validate([
            'apply_form_settings' => 'required|array'
        ]);
        $this->service
            ->setModel($job)
            ->save($request->only(['apply_form_settings']));

        return $job->wasChanged() ? updated_responses('apply_form_updated') : failed_responses(['apply_form_settings' => $request->apply_form_settings]);
    }

    public function sharableLink(JobPost $job): string
    {
        return route('public.jobPost.show_pob_post', ['job_slug' => $job->slug]);
    }

    public function activateJobPost(JobPost $jobPost, Status $status)
    {
        $id = $status
            ->where('type', 'job_post')
            ->where('name', 'status_open')
            ->first();
        $this->service
            ->setModel($jobPost)
            ->save(['status_id' => $id]);

        return updated_responses('job_activated');
    }

    public function closeJobPost(JobPost $jobPost, Status $status)
    {
        $id = $status
            ->where('type', 'job_post')
            ->where('name', 'status_closed')
            ->first();
        $this->service
            ->setModel($jobPost)
            ->save(['status_id' => $id]);

        return updated_responses('job_close');
    }
}
