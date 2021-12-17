<?php

namespace App\Http\Controllers\Tenant\Recruitment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Applicant\Attendee;
use App\Models\Tenant\Recruitment\HiringTeam;
use App\Services\Tenant\Recruitment\HiringTeamService;
use App\Http\Requests\Tenant\Recruitment\HiringTeamRequest;

class HiringTeamController extends Controller
{
    public function __construct(HiringTeamService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service
            ->with(['jobPost:id,name', 'user'])
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function store(HiringTeamRequest $request, HiringTeam $hiringTeam)
    {
        $exist = $hiringTeam
            ->where('job_post_id', $request->job_post_id)
            ->whereIn('recruiter_id', $request->recruiters)->exists();

        if ($exist) {
            return custom_failed_response('member_already_assigned_to_this_job');
        }

        $inputs = $this->generateInputsFromRecruiters($request->job_post_id, $request->recruiters);
        $result = HiringTeam::query()->insert($inputs);

        if (! $result) {
            return custom_failed_response('failed_to_assign_this_members_to_this_job');
        }

        return created_responses('hiring_team');
    }

    private function generateInputsFromRecruiters($job_id, $recruiters)
    {
        $inputs = [];
        foreach ($recruiters as $recruiter) {
            $inputs [] = [
                'job_post_id' => $job_id,
                'recruiter_id' => $recruiter['recruiter_id'],
            ];
        }

        return $inputs;
    }

    public function show(HiringTeam $hiringTeam)
    {
        return $hiringTeam->with(['jobPost:id,name', 'user']);
    }

    public function getTeamsByJob(HiringTeam $hiringTeam, $jobId)
    {
        return $hiringTeam
            ->with(['jobPost:id,name', 'user'])
            ->where('job_post_id', $jobId)
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function destroy(HiringTeam $hiringTeam)
    {
        $id = $hiringTeam->id;

        if (! $hiringTeam->delete()) {
            return custom_failed_response('this_member_could_not_be_deleted_from_hiring_team');
        }

        Attendee::query()->where('hiring_team_id', $id)->delete();

        return deleted_responses('hiring_team');
    }

    public function selectableApplicantAttendees(HiringTeam $hiringTeam, $jobPostId)
    {
        return $hiringTeam->select('id', 'recruiter_id')
            ->with(['user:id,first_name,last_name'])
            ->where('job_post_id', $jobPostId)
            ->get();
    }
}
