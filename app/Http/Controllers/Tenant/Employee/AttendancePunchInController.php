<?php

namespace App\Http\Controllers\Tenant\Employee;

use App\Http\Controllers\Controller;
use App\Models\Core\Auth\User;
use App\Models\Tenant\Attendance\AttendanceDetails;
use App\Services\Tenant\Attendance\AttendanceService;

class AttendancePunchInController extends Controller
{
    public function __construct(AttendanceService $service)
    {
        $this->service = $service;
    }

    public function checkPunchIn()
    {
        /** @var User $user */
        $user = auth()->user();

        return $this->service
            ->setModel($user)
            ->checkPunchIn();
    }

    public function getPunchInTime()
    {
        /** @var User $user */
        $user = auth()->user();

        $this->service
            ->setModel($user)
            ->validatePunchOut();

        return AttendanceDetails::getUnPunchedOut($user->id);
    }
}
