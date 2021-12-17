<?php

namespace App\Services\App\Applicant;

use App\Exceptions\GeneralException;
use App\Models\App\Applicant\JobApplicant;
use App\Models\App\Applicant\Note;
use App\Models\App\Recruitment\HiringTeam;
use App\Models\Core\Auth\User;
use App\Services\App\AppService;

class NoteService extends AppService
{
    private $isNotifiable = true;
    private $attendees = null;

    private $notificationAudience_models = [];

    public function __construct(Note $note)
    {
        $this->model = $note;
    }

    public function getModelObj()
    {
        return $this->model;
    }

    public function setAudiences()
    {
        if (!isset($this->model->id)) {
            throw new GeneralException(__t('can_not_send_notification_to_recruiters'));
        }
        $applicant = JobApplicant::find($this->model->job_applicant_id);
        $recruiters = HiringTeam::where('job_post_id', $applicant->job_post_id)->get()->pluck('recruiter_id');
        $this->notificationAudience_models = User::whereIn('id', $recruiters)->get();
        return $this;
    }

    public function isNotifiable($value, $comparedValue)
    {
        $this->isNotifiable = $value === $comparedValue;
        return $this;
    }

    public function notify($event, $notificationClass): self
    {
        $job_applicant = $this->model->jobApplicant()->first();

        if (class_exists($notificationClass) && $this->isNotifiable && $job_applicant) {
            $audiences = HiringTeam::query()->where('job_post_id', $job_applicant->job_post_id)->get()->pluck('recruiter_id')->toArray();

            notify()
                ->on($event)
                ->mergeAudiences($audiences)
                ->with($this->model)
                ->send($notificationClass);
        }

        return $this;
    }
}
