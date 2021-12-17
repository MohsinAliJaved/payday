<?php

namespace App\Http\Controllers\Tenant\Attendance;

use App\Helpers\Traits\DepartmentAuthentications;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Attendance\AttendanceDetails;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\Attendance\AttendanceStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceStatusController extends Controller
{
    use DepartmentAuthentications;

    public function __construct(AttendanceStatusService $service)
    {
        $this->service = $service;
    }

    public function update(AttendanceDetails $details, Request $request)
    {
        $this->service->validStatus($request->get('status_name'));

        $method = 'attendance' . ucfirst($request->get('status_name'));

        $status_id = resolve(StatusRepository::class)->$method();

        $details = $details->load('status', 'parentAttendanceDetails', 'attendance');

        if (!($request->get('status_name') == 'cancel' &&
            $details->status->name == 'status_pending' && $details->attendance->user->id == auth()->id())){
            $this->departmentAuthentications($details->attendance->user->id);
        }

        DB::transaction(function () use ($details, $status_id, $request) {
            $this->service
                ->setDetails($details)
                ->setAttributes([
                    'status_id' => $status_id,
                    'requestedStatus' => $request->get('status_name'),
                    'previousStatus' => $details->status->name,
                    'review_by' => auth()->id()
                ])
                ->setModel($details->attendance->user)
                ->updateAttendanceDetailsStatus();
        });

        return [
            'status' => true,
            'message' => trans('default.status_updated_response', [
                'name' => __t('attendance'),
                'status' => $request->get('status_name')
            ])
        ];
    }
}
