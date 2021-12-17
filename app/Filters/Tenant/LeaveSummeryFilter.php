<?php


namespace App\Filters\Tenant;


use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use App\Filters\Traits\FilterThroughDepartment;
use App\Filters\Traits\SearchThroughUserFilter;
use App\Filters\Traits\WorkingShiftFilter;
use App\Helpers\Traits\MakeArrayFromString;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class LeaveSummeryFilter extends FilterBuilder
{
    use DateRangeFilter,
        MakeArrayFromString,
        SearchThroughUserFilter,
        WorkingShiftFilter,
        FilterThroughDepartment;

    public function applyDate($date = null)
    {
        $this->builder->when(
            $date,
            fn(Builder $builder) => $builder->whereDate('created_at', Carbon::parse($date)),
            fn(Builder $builder) => $builder->whereDate('created_at', today())
        );
    }

    public function leaveDuration($value = null)
    {
        $this->whereClause('duration_type', $value);
    }

}
