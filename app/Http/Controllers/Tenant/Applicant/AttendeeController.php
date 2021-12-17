<?php

namespace App\Http\Controllers\App\Applicant;

use App\Http\Controllers\Controller;
use App\Models\App\Applicant\Attendee;
use App\Services\App\Applicant\AttendeeService;
use App\Http\Requests\App\Applicant\AttendeeRequest;

class AttendeeController extends Controller
{
    public function __construct(AttendeeService $service)
    {
        $this->service = $service;
    }

    public function store(AttendeeRequest $request)
    {
        $inputs = $request->only(['event_id', 'recruiter_id']);

        $result = $this->service->setAttributes($inputs)->save();

        if (! $result) {
            return custom_failed_response('failed_to_store_attendees');
        }

        return created_responses('attendee');
    }

    public function destroy(Attendee $attendee)
    {
        $attendee->delete();

        return deleted_responses('attendee');
    }
}
