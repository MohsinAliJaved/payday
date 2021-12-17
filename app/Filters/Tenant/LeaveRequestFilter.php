<?php


namespace App\Filters\Tenant;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class LeaveRequestFilter extends LeaveStatusFilter
{
    public function applyDate($date = null)
    {
        $this->builder->when(
            $date,
            fn(Builder $builder) => $builder->whereDate('created_at', Carbon::parse($date)),
        );
    }

}
