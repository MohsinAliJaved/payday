<?php

namespace App\Notifications\App\Applicant;

use App\Mail\Tag\EventCreatedTag;
use App\Models\App\Applicant\Applicant;
use App\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventCreatedNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct($templates, $via, $event)
    {
        $temp = $event->jobApplicant();
        $this->appliedBy = Applicant::query()->find($temp->first()->applicant_id);
        $this->attendees = $event->attendees()->get();
        $this->templates = $templates;
        $this->via = $via;
        $this->model = $event;
        $this->auth = auth()->user();
        $this->tag = EventCreatedTag::class;

        parent::__construct();
    }


    public function parseNotification()
    {
        $this->mailView = 'notification.template';
        $this->databaseNotificationUrl = route('candidates', $this->model->id);

        $this->mailSubject = optional($this->template()->mail())->parseSubject([
            '{event_type}' => $this->model->eventType->name,
        ]);

        $this->databaseNotificationContent = optional($this->template()->database())->parse([
            '{event_type}' => $this->model->eventType->name,
            '{candidate_name}' => $this->appliedBy->full_name,
            '{event_time}' => $this->model->start_at . ' - ' . $this->model->end_at
        ]);
    }
}