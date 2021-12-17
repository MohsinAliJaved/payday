<?php

namespace App\Services\Tenant\Company;

use App\Models\App\Company\CareerPage;
use App\Services\Core\BaseService;

class CareerPageService extends BaseService
{
    public function __construct(CareerPage $careerPage)
    {
        $this->model = $careerPage;
    }
}