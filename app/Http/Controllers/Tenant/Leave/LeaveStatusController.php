<?php

namespace App\Http\Controllers\Tenant\Leave;

use App\Helpers\Traits\DepartmentAuthentications;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Leave\Leave;
use App\Services\Tenant\Leave\LeaveStatusService;
use Illuminate\Support\Facades\DB;

class LeaveStatusController extends Controller
{
    use DepartmentAuthentications;

    public function __construct(LeaveStatusService $service)
    {
        $this->service = $service;
    }

    public function update(Leave $leave)
    {
        $status = request()->get('status_name');

        DB::transaction(function () use ($leave, $status) {
            $this->service
                ->setModel($leave->user->load('department'))
                ->setStatusName($status)
                ->setLeave($leave->load('lastStatus', 'status'))
                ->setSettings()
                ->setAttr('note', request()->get('note'))
                ->setStatusAttr()
                ->setDepartment()
                ->validationsAndSetCredentials()
                ->validateManger()
                ->addLeaveReview()
                ->when($this->service->isNeedToUpdateLeave, function (LeaveStatusService $service) {
                    $service->updateLeaveStatus();
                })->sendNotification($this->service->leave);
        });

        return updated_responses('leave');
    }
}
