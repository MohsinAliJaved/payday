<?php

namespace App\Mail\Tag;

use App\Models\App\Applicant\Applicant;
use App\Models\App\JobPost\JobPost;

class EventCreatedTag extends Tag
{
    public function __construct($event, $notifier = null, $receiver = null)
    {
        $temp = $event->jobApplicant();
        $this->appliedBy = Applicant::query()->find($temp->first()->applicant_id);
        $this->jobPost = JobPost::query()->find($temp->first()->job_post_id);
        $this->event = $event;
        $this->notifier = $notifier;
        $this->receiver = $receiver;
        $this->auth = auth()->user();
        $this->resourceurl = '';
    }

    function notification()
    {
        return array_merge([
            '{receiver_name}' => $this->receiver->full_name,
            '{action_by}' => $this->auth,
            '{event_type}' => $this->event->eventType->name,
            '{candidate_name}' => $this->appliedBy->full_name,
            '{event_time}' => $this->event->start_at . ' - ' . $this->event->end_at,
            '{job_post}' => $this->jobPost->name,
            '{event_location}' => $this->event->location,
            '{app_name}' => config('settings.application.company_name')
        ], $this->common());
    }
}