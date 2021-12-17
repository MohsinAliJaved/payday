<?php

namespace App\Services\App\Applicant;

use App\Models\App\Applicant\ApplicationAnswer;
use App\Services\App\AppService;

class ApplicationAnswerService extends AppService
{
    public function __construct(ApplicationAnswer $answers)
    {
        $this->model = $answers;
    }
}