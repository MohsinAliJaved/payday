<?php

namespace App\Http\Controllers\Tenant\Recruitment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\JobPost\JobPost;
use App\Models\Tenant\Recruitment\JobStage;
use App\Models\Tenant\Applicant\JobApplicant;
use App\Helpers\Tenant\AppOnDeleteRelatedModels;
use App\Services\Tenant\Recruitment\JobStageService;
use App\Http\Requests\Tenant\Recruitment\JobStageRequest;

class JobStageController extends Controller
{
    public function __construct(JobStageService $service)
    {
        $this->service = $service;
    }

    public function index(): object
    {
        return $this->service
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function store(JobStageRequest $request): array
    {
        $result = $this->service
            ->setAttributes($request->only('name', 'job_post_id'))
            ->save();

        if (! $result) {
            return failed_responses([$request->all()]);
        }
        $jobPost = JobPost::find($request->job_post_id);
        $jobPost->stages = $request->ordered_stages;
        $jobPost->save();

        return created_responses('job_stage');
    }

    public function show(JobStage $jobStage): object
    {
        return $jobStage;
    }

    private function changeJobPostStages($oldName = null, $newName, $jobPostId)
    {
        $jobPost = JobPost::find($jobPostId);

        if (! $jobPost) {
            return;
        }
        $stages = $jobPost->stages;
        $stages = explode(',', $stages);
        for ($i = 0; $i < count($stages); $i++) {
            if ($stages[$i] === $oldName) {
                $stages[$i] = $newName;
                break;
            }
        }
        $jobPost->stages = implode(',', $stages);
        $jobPost->save();
    }

    public function update(JobStageRequest $request, JobStage $jobStage)
    {
        $oldName = $jobStage->name;
        $jobStage->name = $request->name;
        $jobStage->save();

        if (! $jobStage->wasChanged('name')) {
            return failed_responses(['stage_name' => $request->name]);
        }
        $this->changeJobPostStages($oldName, $request->name, $jobStage->job_post_id);

        return updated_responses('job_stage');
    }

    private function deleteJobPostStages($stage_name, $jobPostId)
    {
        $jobPost = JobPost::find($jobPostId);

        if (! $jobPost) {
            return;
        }
        $stages = $jobPost->stages;
        $stages = explode(',',$stages);
        for($i =0; $i< count($stages) ; $i++){
            if($stages[$i] === $stage_name){
                array_splice($stages,$i,1);
                break;
            }
        }
        $jobPost->stages = implode(',', $stages);
        $jobPost->save();
    }

    public function destroy(JobStage $jobStage, AppOnDeleteRelatedModels $model)
    {
        $jobPostId = $jobStage->job_post_id;
        $stage_name = $jobStage->name;

        $model = $model->setModel($jobStage);
        $data = $model->loadRelatedModelsOnDeleteJObStage();
        $model->removeData();
        if (! $jobStage->delete()) {
            return failed_responses(['status' => false, 'message' => 'Could not Delete']);
        }
        // Remove this Stage name  from job post
        $this->deleteJobPostStages($stage_name, $jobPostId);

        return deleted_responses('job_stage');
    }

    public function moveApplicants(Request $request, JobStage $jobStage, JobApplicant $jobApplicant, $stage_id, AppOnDeleteRelatedModels $model)
    {
        $request->validate([
            'next_stage_id' => 'required|exists:job_stages,id',
        ]);

        $stage = $jobStage->find($stage_id);
        $status_id = null;

        switch (strtolower(trim($stage->name))) {
            case 'new':
                $status_id = resolve(StatusRepository::class)->getStatusId('job_applicant', 'status_new');
              break;
            case 'hired':
                $status_id = resolve(StatusRepository::class)->getStatusId('job_applicant', 'status_hired');
              break;
            case 'disqualified':
                $status_id = resolve(StatusRepository::class)->getStatusId('job_applicant', 'status_disqualified');
              break;
            default:
                $status_id = resolve(StatusRepository::class)->getStatusId('job_applicant', 'status_in_progress');
          }

        //Moving JobApplicant move which current Moved stage id with Status id == That stage's status.
        $jobApplicant->where('current_stage_id', intval($stage_id))
            ->update([
                'status_id' => $status_id,
                'current_stage_id' => intval($request->next_stage_id),
            ]);

        // Delete Stage
        $model = $model->setModel($stage);
        $data = $model->loadRelatedModelsOnDeleteJObStage();
        $stage->delete();

        return deleted_responses('job_stage');
    }

    public function getStagesByJob(JobStage $jobStage, $job_post_id)
    {
        return $jobStage->select('id', 'job_post_id', 'name')
            ->with([
                'jobApplicants:id,applicant_id,review',
                'jobApplicantCount',
                'jobApplicants.appliedBy',
            ])
            ->where('job_post_id', $job_post_id)->get();
    }
}
