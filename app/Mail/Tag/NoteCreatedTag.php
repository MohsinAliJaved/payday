<?php

namespace App\Mail\Tag;

use App\Models\App\Applicant\Applicant;
use App\Models\App\JobPost\JobPost;

class NoteCreatedTag extends Tag
{
    public function __construct($note, $notifier = null, $audiences)
    {
        $this->model = $note;
        $this->notedBy = $note->notedBy;
        $this->jobApplicant = $note->jobApplicant;
        $this->applicant = Applicant::find($note->jobApplicant->applicant_id);
        $this->jobPost = JobPost::find($this->jobApplicant->job_post_id);

        $this->notifier = $notifier;
        $this->receiver = $audiences->full_name;
        $this->resourceurl = '';
    }

    function notification()
    {
        return array_merge([
            '{receiver_name}' => $this->receiver,
            '{noted_by}' => $this->notedBy->full_name,
            '{job_post}' => $this->jobPost->name,
            '{candidate_name}' => $this->applicant->full_name,
            '{app_name}' => config('settings.application.company_name'),
            '{app_logo}' => config('settings.application.company_logo')
        ], $this->common());
    }

}