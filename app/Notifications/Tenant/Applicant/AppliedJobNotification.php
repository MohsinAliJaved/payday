<?php

namespace App\Notifications\Tenant\Applicant;

use App\Mail\Tag\AppliedJobTag;
use App\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppliedJobNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct($templates, $via, $jobApplicant)
    {
        $this->templates = $templates;
        $this->via = $via;
        $this->model = $jobApplicant;
        $this->auth = auth()->user();
        $this->tag = AppliedJobTag::class;
        $this->applicant = $this->model->appliedBy;
        $this->jobPost = $this->model->jobPost;


        parent::__construct();
    }

    public function parseNotification()
    {
        $this->mailView = 'notification.template';
        $this->databaseNotificationUrl = route('candidates', $this->applicant->id);
        $this->mailSubject = optional($this->template()->mail())->parseSubject([
            '{candidate_name}' => $this->applicant->full_name,
            '{job_post}' => $this->jobPost->name,
        ]);
        $this->databaseNotificationContent = optional($this->template()->database())->parse([
            '{job_post}' => $this->jobPost->name,
            '{candidate_name}' => $this->applicant->full_name,
        ]);
    }
}