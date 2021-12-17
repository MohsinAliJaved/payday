<?php

namespace App\Http\Controllers\Tenant\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Applicant\Event;
use App\Services\Tenant\Applicant\EventService;
use App\Http\Requests\Tenant\Applicant\EventRequest;

class EventController extends Controller
{
    public function __construct(EventService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service
            ->with([
                'attendees.hiringTeam.user.profilePicture', 'eventType', 'jobApplicant.appliedBy', 'jobApplicant.jobPost:id,name'
            ])
            ->whereHas('attendees.hiringTeam', function ($query) {
                $query->where('recruiter_id', auth()->id());
            })
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }


    private function loggingData($lang, $find, $replace, $auth = null, $model)
    {
        //Store to timeline
        $description = trans('default.' . $lang);
        $description = str_replace($find, $replace, $description);
        log_to_database($description, [], 'timeline', $auth, $model);
    }

    public function store(EventRequest $request)
    {
        $attendees = $request->attendees ?? [];

        $result = $this->service
            ->setAttributes($request->only('event_type_id',
                'start_at',
                'end_at',
                'location',
                'job_applicant_id',
                'description')
            )
            ->saveEvent();

        $event = $result->getModelObj();

        $result->addAttendees($attendees)
            ->notify('event_created', EventCreatedNotification::class);

        //Store to timeline
        $attendees = [];
        foreach ($event->attendees as $attendee) {
            $attendees[] = $attendee->hiringTeam->user->full_name;
        }

        $auth = auth()->user();
        $this->loggingData('timeline_for_create_event',
            array('{candidate_name}', '{event_type}', '{attendees}', '{date}'),
            array($event->jobApplicant->appliedBy->full_name,
                $event->eventType->name,
                join(", ", $attendees),
                $event->start_at),
            $auth, $event->jobApplicant);

        if (!$result) {
            return failed_responses();
        }

        return created_responses('event');
    }

    public function show(Event $e, $id)
    {
        return $e
            ->with(['attendees'])
            ->find($id);
    }

    public function update(EventRequest $request, Event $event)
    {
        $attendees = $request->attendees ?? [];
        if ($attendees) {
            Attendee::query()->where('event_id', $event->id)->delete();
        }
        $this->service
            ->setModel($event)
            ->addAttendees($attendees)
            ->save($request->only(['event_type_id', 'start_at', 'start_at', 'end_at', 'location', 'description',]));

        return updated_responses('job_applicant');
    }

    public function destroy(Event $event)
    {
        $id = $event->id;
        if ($event->delete()) {
            Attendee::query()->where('event_id', $id)->delete();
        }
        return deleted_responses('event');
    }

    public function jobApplicantEventList($id)
    {
        return $this->service
            ->with([
                'attendees.hiringTeam.user.profilePicture', 'eventType', 'jobApplicant.appliedBy', 'jobApplicant.jobPost:id,name'
            ])
            ->where('job_applicant_id', $id)
            ->latest()
            ->get();
    }
}
