<?php

namespace App\Http\Controllers\Tenant\Leave;

use App\Helpers\Traits\SettingKeyHelper;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Attendance\Attendance;
use App\Models\Tenant\Leave\Leave;
use Illuminate\Support\Carbon;

class LeaveLogController extends Controller
{
    use SettingKeyHelper;

    public function index(Leave $leave): Leave
    {
        $leave = $leave->load([
            'user:id,first_name,last_name',
            'user.department.parentDepartment:id',
            'assignedBy:id,first_name,last_name',
            'type:id,name',
            'attachments',
            'lastReview',
            'lastReview.department:id,manager_id',
            'comments',
            'reviews:id,leave_id,reviewed_by,status_id,created_at,department_id',
            'reviews.department:id,department_id',
            'reviews.reviewedBy:id,first_name,last_name',
            'reviews.status:id,name',
            'reviews.comments',
            'status:id,name'
        ]);

        $leave->attendances = Attendance::query()
            ->with('details')
            ->where('user_id', $leave->user->id)
            ->whereBetween('in_date', [Carbon::parse($leave->start_at)->toDateString(), Carbon::parse($leave->end_at)->toDateString()])
            ->get();

        $leaveApprovalLevel = $this->getSettingFromKey('leave')('approval_level') ?: 'single';
        $leave->allowBypass = $leaveApprovalLevel == 'multi' ?
            !!$this->getSettingFromKey('leave')('allow_bypass') ?: false : false;

        return $leave;
    }
}
