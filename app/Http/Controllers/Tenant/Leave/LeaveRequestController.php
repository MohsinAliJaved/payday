<?php

namespace App\Http\Controllers\Tenant\Leave;

use App\Filters\Tenant\LeaveRequestFilter;
use App\Helpers\Traits\UserAccessQueryHelper;
use App\Http\Controllers\Controller;
use App\Models\Core\Auth\User;
use App\Models\Tenant\Leave\Leave;
use App\Repositories\Core\Status\StatusRepository;
use App\Services\Tenant\Leave\LeaveRequestService;
use Illuminate\Database\Eloquent\Builder;

class LeaveRequestController extends Controller
{
    use UserAccessQueryHelper;

    public function __construct(LeaveRequestFilter $filter, LeaveRequestService $service)
    {
        $this->filter = $filter;
        $this->service = $service;
    }

    public function index()
    {
        return Leave::filters($this->filter)
            ->with($this->service->relations())
            ->latest()
            ->when(request()->has('rejected') && request()->get('rejected') == 'true',
                fn(Builder $builder) => $builder
                    ->where('status_id', resolve(StatusRepository::class)->leaveRejected()),
                fn(Builder $builder) => $builder
                    ->where('status_id', resolve(StatusRepository::class)->leavePending())
            )->paginate(request()->get('per_page', 10));
    }
}
