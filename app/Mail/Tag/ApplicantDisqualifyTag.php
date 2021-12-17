<?php


namespace App\Mail\Tag;


class ApplicantDisqualifyTag extends Tag
{
    protected $jobAttendant;

    public function __construct($jobApplicant, $notifier = null, $audiences)
    {
        $this->jobApplicant = $jobApplicant;
        $this->notifier = $notifier;
        $this->receiver = $audiences->full_name;
        $this->resourceurl = '';
    }

    function notification()
    {
        return array_merge([
            '{receiver_name}' => $this->receiver,
            '{candidate_name}' => optional($this->jobApplicant->appliedBy)->full_name,
            '{job_post}' => optional($this->jobApplicant->jobPost)->name ?? __t('title') . ' ' . __t('not_given'),
            '{disqualification_reason}' => $this->jobApplicant->disqualification_reason ?? __t('disqualification_reason') . ' ' . __t('not_given')
        ], $this->common());
    }
}