<?php

namespace App\Notifications\App\Applicant;

use App\Mail\Tag\ApplicantDisqualifyTag;
use App\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplicantDisqualifyNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct($templates, $via, $jobApplicant)
    {
        $this->templates = $templates;
        $this->via = $via;
        $this->model = $jobApplicant;
        $this->auth = auth()->user();
        $this->tag = ApplicantDisqualifyTag::class;

        parent::__construct();
    }

    public function parseNotification()
    {
        $this->mailView = 'notification.template';
        $this->databaseNotificationUrl = route('candidates', $this->model->id);

        $this->mailSubject = optional($this->template()->mail())->parseSubject([
            '{candidate_name}' => $this->model->appliedBy->fullName,
            '{app_name}' => config('settings.application.company_name')
        ]);

        $this->databaseNotificationContent = optional($this->template()->database())->parse([
            '{candidate_name}' => $this->model->appliedBy->fullName,
            '{job_post}' => $this->model->jobPost->name,
            '{action_by}' => $this->auth->fullName
        ]);
    }
}