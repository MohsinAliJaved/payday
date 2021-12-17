<?php

namespace App\Services\Tenant\Applicant;

use App\Services\App\AppService;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Mail;
use App\Services\Tenant\TenantService;
use App\Mail\App\SendApplicantCustomMail;
use App\Models\Tenant\Recruitment\JobStage;
use App\Helpers\Traits\AppCustomEmailHelper;
use App\Models\Tenant\Applicant\JobApplicant;
use App\Models\Tenant\Recruitment\HiringTeam;

class JobApplicantService extends TenantService
{
    use AppCustomEmailHelper;

    private $isNotifiable = true;
    private $notifyCandidateForJobApply = false;
    private $template = [];

    public function __construct(JobApplicant $jobApplicant)
    {
        $this->model = $jobApplicant;
    }

    public function setNotifyCandidateForJobApply($status = true)
    {
        $this->notifyCandidateForJobApply = $status;
        return $this;
    }

    public function disqualifyCandidate()
    {
        $stage = JobStage::query()
            ->where('name', 'disqualified')
            ->where('job_post_id', $this->model->job_post_id)
            ->first();

        throw_if(!$stage, new GeneralException(__t('action_not_allowed')));

        $this->model->current_stage_id = intval($stage->id);
        $this->model->status_id = intval($this->getAttribute('status_id'));
        $this->model->disqualification_reason = $this->getAttribute('disqualification_reason');
        $this->model->save();

        return $this;
    }

    public function notify($event, $notificationClass)
    {
        if (class_exists($notificationClass) && $this->isNotifiable) {
            $audiences = HiringTeam::query()->where('job_post_id', $this->model->jobPost->id)->get()->pluck('recruiter_id');

            if (intval(request()->notify) === 1) {
                $this->sendCandidateEmail();
            }

            if ($this->notifyCandidateForJobApply) {

                $this->jobApplyResponseForCandidate()
                    ->notifyCandidateForSuccessfulApplication();
            }

            notify()
                ->on($event)
                ->mergeAudiences($audiences->toArray())
                ->with($this->model)
                ->send($notificationClass);
        }
        return $this;
    }

    public function jobApplyResponseForCandidate()
    {
        $applyJobTemplate = $this->getOneTemplate('job_apply_response_for_candidate');

        $template = $this->getTemplate($applyJobTemplate);

        $jobApplicant = $this->model;

        $logo = config()->get('settings.application.company_logo');

        $replaceableValues = [
            '{job_post}' => $jobApplicant->jobPost->name,
            '{candidate_name}' => $jobApplicant->appliedBy->name,
            '{app_logo}' => asset(empty($logo) ? '/images/logo.png' : $logo),
            '{app_name}' => config('app.name'),
        ];

        $subject = count($applyJobTemplate->templates) > 0 ? optional($applyJobTemplate)->templates[0]->subject : '';

        $this->template = (object)array(
            'mail' => strtr($template, $replaceableValues),
            'subject' => strtr($subject, $replaceableValues)
        );

        return $this;

    }

    public function isNotifiable($value, $comparedValue)
    {
        $this->isNotifiable = $value === $comparedValue;
        return $this;
    }

    public function sendCandidateEmail()
    {
        request()->validate([
            'subject' => 'required|string',
            'mail' => 'required|string'
        ]);
        Mail::to($this->model->appliedBy->email)
            ->send(new SendApplicantCustomMail(request()));
        return $this;
    }

    public function notifyCandidateForSuccessfulApplication()
    {
        Mail::to($this->model->appliedBy->email)
            ->send(new SendApplicantCustomMail((object)$this->template));

        return $this;
    }
}