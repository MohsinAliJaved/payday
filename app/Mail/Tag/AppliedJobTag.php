<?php


namespace App\Mail\Tag;

class AppliedJobTag extends Tag
{
    public function __construct($jobApplicant, $notifier = null, $audience)
    {
        $this->model = $jobApplicant;
        // $this->notifier = $notifier;
        $this->receiver = $audience;
        $this->resourceurl = '';
        $this->applicant = $this->model->appliedBy;
        $this->jobPost = $this->model->jobPost;
    }

    function notification()
    {
        return array_merge([
            '{receiver_name}' => $this->receiver->full_name,
            '{job_post}' => $this->jobPost->name,
            '{candidate_name}' => $this->applicant->full_name,
        ], $this->common());
    }
}