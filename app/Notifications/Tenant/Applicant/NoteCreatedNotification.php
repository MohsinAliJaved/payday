<?php

namespace App\Notifications\App\Applicant;

use App\Mail\Tag\NoteCreatedTag;
use App\Models\App\Applicant\Applicant;
use App\Models\App\JobPost\JobPost;
use App\Notifications\BaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class NoteCreatedNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct($templates, $via, $note)
    {
        $this->templates = $templates;
        $this->via = $via;
        $this->model = $note;
        $this->auth = auth()->user();
        $this->tag = NoteCreatedTag::class;
        
        $this->notedBy = $this->model->notedBy->first()->fullName;
        $this->jobApplicant = $this->model->jobApplicant()->first();
        $this->applicantId = $this->jobApplicant->applicant_id;
        $this->jobPost = JobPost::find($this->jobApplicant->job_post_id);
        $this->applicant = Applicant::find($this->jobApplicant->applicant_id);

        parent::__construct();
    }


    public function parseNotification()
    {
        
        $this->mailView = 'notification.template';
        $this->databaseNotificationUrl = route('candidates', $this->applicantId);

        $this->mailSubject = optional($this->template()->mail())->parseSubject([
            '{noted_by}' => $this->notedBy,
        ]);

        $this->databaseNotificationContent = optional($this->template()->database())->parse([

            '{noted_by}' => $this->notedBy,
            '{job_post}' => $this->jobPost->name ,
            '{candidate_name}' => $this->applicant->fullName,

        ]);
    }
}