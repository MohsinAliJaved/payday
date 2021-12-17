<?php


namespace App\Services\Tenant\Recruitment;


use App\Services\Tenant\TenantService;
use App\Models\Tenant\Recruitment\Todo;

class TodoService extends TenantService
{
    public function __construct(Todo $todo)
    {
        $this->model = $todo;
    }

}