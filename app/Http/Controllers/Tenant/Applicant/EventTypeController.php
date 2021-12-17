<?php

namespace App\Http\Controllers\App\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\Applicant\EventTypeRequest;
use App\Models\App\Applicant\EventType;
use App\Services\App\Applicant\EventTypeService;

class EventTypeController extends Controller
{
    public function __construct(EventTypeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function store(EventTypeRequest $request)
    {
        $result = $this->service
            ->setAttributes($request->only(['name']))
            ->save();

        if (!$result) {
            return failed_responses();
        }
        return created_responses('event_type');
    }

    public function show(EventType $eventType)
    {
        return $eventType;
    }

    public function update(EventTypeRequest $request, EventType $eventType)
    {
        $this->service
            ->setModel($eventType)
            ->save(
                $request->only(['name'])
            );

        return updated_responses('event_type');
    }

    public function destroy(EventType $eventType)
    {
        $eventType->delete();

        return deleted_responses('applicant');
    }

    public function getEventTypes(EventType $eventType)
    {
        return $eventType->all('id', 'name');
    }
}
