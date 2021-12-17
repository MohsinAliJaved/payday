<?php

namespace App\Services\Tenant\Applicant;

use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Mail;
use App\Models\Tenant\Applicant\Attendee;
use App\Models\Tenant\Applicant\Event;
use App\Services\Tenant\TenantService;
use App\Mail\App\SendApplicantCustomMail;
use App\Models\Tenant\Recruitment\HiringTeam;
use App\Models\Core\Setting\NotificationEvent;

class EventService extends TenantService
{
    private $isNotifiable = true;
    private $attendees = null;
    private $template = [];

    public function __construct(Event $event)
    {
        $this->model = $event;
    }

    public function getModelObj()
    {
        return $this->model;
    }

    public function notify($event, $notificationClass): self
    {
        if (class_exists($notificationClass) && $this->isNotifiable) {

            $this->getCreateEventTemplateForCandidate()
                ->sendCandidateEmail();

            notify()
                ->on($event)
                ->mergeAudiences($this->generateAudience())
                ->with($this->model)
                ->send($notificationClass);
        }

        return $this;
    }

    public function generateAudience(): array
    {
        $userIds = [];

        foreach ($this->attendees as $attendee) {
            $user_id = HiringTeam::query()->find($attendee->hiring_team_id);
            array_push($userIds, $user_id->recruiter_id);
        }

        sort($userIds);
        return $userIds;
    }

    public function saveEvent($options = [])
    {
        $attributes = count($options) ? $options : request()->all();

        $this->model
            ->fill($this->getFillAble($attributes))
            ->save();

        return $this;
    }

    public function isNotifiable($value, $comparedValue)
    {
        $this->isNotifiable = $value === $comparedValue;
        return $this;
    }

    public function addAttendees($attendees)
    {
        throw_if(!isset($this->model->id), new GeneralException(__t('action_not_allowed')));

        $event_id = $this->model->id;

        if (count($attendees) < 1) {
            return $this;
        }

        $attendee = [];
        foreach ($attendees as $att) {
            $result = Attendee::query()->create([
                'event_id' => $event_id,
                "hiring_team_id" => $att['hiring_team_id']
            ]);

            array_push($attendee, $result);
        }

        $this->attendees = $attendee;
        return $this;
    }

    public function sendCandidateEmail()
    {
        Mail::to($this->model->jobApplicant->appliedBy->email)
            ->send(new SendApplicantCustomMail($this->template));

        return $this;

    }

    public function getCreateEventTemplateForCandidate()
    {
        $notificationEvent = NotificationEvent::with('templates')
            ->where('name', 'create_event_mail_for_candidate')
            ->first();

        $template = $this->getTemplate($notificationEvent);

        $jobApplicant = $this->model->jobApplicant;

        $logo = config()->get('settings.application.company_logo');
        $replaceableValues = [
            '{description}' => $this->model->description,
            '{location}' => $this->model->location,
            '{start_at}' => $this->model->start_at,
            '{end_at}' => $this->model->end_at,
            '{event_type}' => $this->model->eventType->name,
            '{job_post}' => $jobApplicant->jobPost->name,
            '{candidate_name}' => $jobApplicant->appliedBy->name,
            '{app_logo}' => asset(empty($logo) ? '/images/logo.png' : $logo),
            '{app_name}' => config('app.name'),
        ];

        $subject = count($notificationEvent->templates) > 0 ? optional($notificationEvent)->templates[0]->subject : '';

        $this->template = (object)array(
            'mail' => strtr($template, $replaceableValues),
            'subject' => strtr($subject, $replaceableValues)
        );

        return $this;
    }

    private function getTemplate($notificationEvent)
    {
        if (count(optional($notificationEvent)->templates) > 0) {
            return optional($notificationEvent)->templates[0]->custom_content
                ? optional($notificationEvent)->templates[0]->custom_content
                : optional($notificationEvent)->templates[0]->default_content;
        }
        return '';
    }
}
