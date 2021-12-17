<?php

namespace App\Http\Controllers\Tenant\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Company\Department;
use App\Services\Tenant\Employee\DepartmentService;
use App\Http\Requests\Tenant\Company\DepartmentRequest;

class DepartmenteController extends Controller
{
    public function __construct(DepartmentService $service)
    {
        $this->service = $service;
    }

    public function index(): object
    {
        return $this->service
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function store(DepartmentRequest $request): array
    {
        $this->service
            ->setAttributes($request->only('name'))
            ->save();

        return created_responses('department');
    }

    public function show(Department $department): object
    {
        return $department;
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        $this->service
            ->setModel($department)
            ->setAttributes($request->only('name'))
            ->save();

        return updated_responses('department');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return deleted_responses('department');
    }

    public function getAllDepartments(Department $department)
    {
        return $department->all();
    }
}
