<?php

namespace App\Http\Controllers\App\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\Applicant\ApplicationAnswerRequest;
use App\Models\App\Applicant\ApplicationAnswer;
use App\Services\App\Applicant\ApplicationAnswerService;

class ApplicationAnswerController extends Controller
{
    public function __construct(ApplicationAnswerService $service)
    {
        $this->service = $service;
    }

    public function index(): object
    {
        return $this->service
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function store(ApplicationAnswerRequest $request, $jobApplicantId = null): array
    {
        if (intval($jobApplicantId) > 0) {
            $inputs = $request->only(['application_answer_record']);
            $newData = ['job_applicant_id' => $jobApplicantId];
            $inputs = array_merge($inputs, $newData);
        }

        $this->service
            ->setAttributes($request->only('name'))
            ->save();

        return created_responses('application_answer');
    }

    public function storeAnswers(ApplicationAnswerRequest $request, $jobApplicantId = null): array
    {
        if (intval($jobApplicantId) > 0) {
            $inputs = $request->only(['application_answer_record']);
            $newData = ['job_applicant_id' => $jobApplicantId];
            $inputs = array_merge($inputs, $newData);
        }

        $this->service
            ->setAttributes($request->only('name'))
            ->save();

        return created_responses('application_answer');
    }

    public function show(ApplicationAnswer $applicant): object
    {
        return $applicant;
    }

    public function update(ApplicationAnswerRequest $request, ApplicationAnswer $applicant)
    {
        $this->service
            ->setModel($applicant)
            ->save(
                $request->only('name')
            );

        return updated_responses('application_answer');
    }

    public function destroy(ApplicationAnswer $applicant)
    {
        $applicant->delete();

        return deleted_responses('application_answer');
    }
}
