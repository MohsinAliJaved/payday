<?php

namespace App\Http\Controllers\Tenant\Recruitment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Recruitment\Stage;
use App\Services\Tenant\Recruitment\StageService;
use App\Http\Requests\Tenant\Recruitment\StageRequest;

class StageController extends Controller
{
    public function __construct(StageService $service)
    {
        $this->service = $service;
    }

    public function index(): object
    {
        return $this->service
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function store(StageRequest $request): array
    {
        $this->service
            ->setAttributes($request->only('name'))
            ->save();

        return created_responses('stage');
    }

    public function show(Stage $stage): object
    {
        return $stage;
    }

    public function update(StageRequest $request, Stage $stage)
    {
        $this->service
            ->setModel($stage)
            ->setAttributes($request->only('name'))
            ->save();

        return updated_responses('stage');
    }

    public function destroy(Stage $stage)
    {
        $stage->delete();

        return deleted_responses('stage');
    }
}
