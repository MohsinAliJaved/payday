<?php

namespace App\Http\Controllers\Tenant\JobPost;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\JobPost\JobPost;
use App\Models\Tenant\Recruitment\Stage;
use App\Models\Tenant\Recruitment\JobStage;
use App\Filters\Tenant\JobPost\JobPostFilter;
use App\Models\Tenant\Recruitment\HiringTeam;
use App\Services\Tenant\JobPost\JobPostService;
use App\Helpers\Tenant\AppOnDeleteRelatedModels;
use App\Repositories\Core\Status\StatusRepository;
use App\Http\Requests\Tenant\JobPost\JobPostRequest;

class JobPostController extends Controller
{
    public function __construct(JobPostService $service, JobPostFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index(): object
    {
        return $this->service
            ->filters($this->filter)
            ->with([
                'location:id,address',
                'department:id,name',
                'jobType:id,name',
                'status:id,name,class,type',
                'jobStages:id,job_post_id,name',
                'jobStages' => function ($query) {
                    $query->select('id', 'name', 'job_post_id')
                        ->with([
                            'jobApplicantCount',
                            'jobApplicants.appliedBy:id,first_name,last_name,email',
                            'jobApplicants.status:id,name,class,type',
                        ]);
                },

                'totalApplicants',
            ])
            ->when(! request()->get('status'), function ($query) {
                $query->where('status_id', '!=', resolve(StatusRepository::class)->getStatusId('job_post', 'status_closed'));
            })
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    private function getJobStages($stages, $jobId)
    {
        $stages = explode(',', $stages);
        $stageArray = [];
        foreach ($stages as $stage) {
            $stageArray[] = ['job_post_id' => $jobId, 'name' => $stage];
        }

        return $stageArray;
    }

    public function store(JobPostRequest $request): array
    {
        $inputs = $request->only([
            'company_location_id', 'department_id', 'job_type_id', 'name', 'stages', 'salary',
            'description', 'responsibilities', 'job_post_settings', 'apply_form_settings', 'last_submission_date',
        ]);
        $newData = ['posted_by' => auth()->id(), 'slug' => Str::uuid(), 'status_id' => resolve(StatusRepository::class)->getStatusId('job_post', 'status_draft')];
        $inputs = array_merge($inputs, $newData);

        $result = $this->service
            ->setAttributes($inputs)
            ->save();

        if ($result) {
            $jobStage = $this->getJobStages($request->stages, $result->id);
            JobStage::query()->insert($jobStage);
            HiringTeam::create([
                'job_post_id' => $result->id,
                'recruiter_id' => auth()->id(),
            ]);

            return created_responses('job_post');
        }

        return custom_failed_response('failed_to_create_job');
    }

    public function show(JobPost $job, $id)
    {
        return $job->with([
            'location:id,address',
            'department:id,name',
            'jobType:id,name',
            'jobPostThumbnail',
            'status:id,name,class,type',
            'jobStages' => function ($query) {
                $query->select('id', 'name', 'job_post_id')
                    ->with([
                        'jobApplicantCount',
                    ]);
            },
        ])->find($id);
    }

    public function update(JobPostRequest $request, JobPost $jobPost)
    {
        $this->service
            ->setModel($jobPost)
            ->save(
                $request->only([
                    'company_location_id', 'department_id', 'job_type_id', 'name', 'salary',
                    'description', 'responsibilities', 'job_post_settings', 'apply_form_settings', 'last_submission_date',
                ])
            );

        return updated_responses('job_post');
    }

    public function destroy(JobPost $jobPost, AppOnDeleteRelatedModels $model)
    {
        $model = $model->setModel($jobPost);
        $data = $model->loadRelatedModelsOnDeleteJobPost();
        $model->removeData();
        $jobPost->delete();
        return deleted_responses('job_post');
    }

    public function getStages(JobPost $jobPost, $job_post_id)
    {
        return $jobPost
            ->select('id', 'name', 'slug', 'created_at', 'stages')
            ->with([
                'jobStages:id,job_post_id,name',
            ])->find($job_post_id);
    }

    public function addStages(Request $request, JobPost $jobPost, JobStage $jobStage, $jobId)
    {
        $request->validate([
            'new_stages' => 'nullable|string',
            'final_sorted_stages' => 'required|string',
        ]);
        $jobPost = $jobPost->find($jobId);
        $jobPost->stages = $request->final_sorted_stages;
        $jobPost->save();

        if (isset($request->new_stages) && $request->new_stages != null) {
            $new = [];
            $stages = explode(',', $request->new_stages);
            foreach ($stages as $stage) {
                $new[] = ['job_post_id' => intval($jobId), 'name' => $stage];
            }
            $jobStage->insert($new);

            return created_responses('job_stage');
        }
    }

    public function reorderStages(Request $request, JobPost $jobPost, $jobId)
    {
        $request->validate([
            'final_sorted_stages' => 'required|string',
        ]);
        $jobPost = $jobPost->find($jobId);
        $jobPost->stage = $request->final_sorted_stages;
        $jobPost->save();

        return created_responses('job_stage_reordered');
    }

    //--------------------Aggregate Methods------------------------------------------------------

    public function changeStatus(JobPost $jobPost, Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required',
        ]);

        $jobPost = $jobPost->find($id);
        $jobPost->status_id = intval($request->status_id);
        $jobPost->save();

        return status_response('job_post', strtolower(__t($jobPost->load('status')->status->name)));
    }

    public function selectableStages()
    {
        return Stage::query()->get(['id', 'name']);
    }

    public function selectableOpenJobPost()
    {
        $status_open_id = resolve(StatusRepository::class)->getStatusId('job_post', 'status_open');

        return JobPost::query()->where('status_id', $status_open_id)->get(['id', 'name']);
    }
}
