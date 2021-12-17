<?php


namespace App\Filters\Tenant\Applicant;

use App\Filters\FilterBuilder;
use App\Filters\Traits\StatusFilter;
use App\Filters\Traits\DateRangeFilter;
use App\Filters\Traits\SearchFilterTrait;

class JobApplicantFilter extends FilterBuilder
{
    use DateRangeFilter, SearchFilterTrait, StatusFilter;

    public function review($review = null)
    {
        if ($review) {
            $this->whereClause('review', $review, "=");
        }
    }

    public function hiringStage($hiringStage = null)
    {
        if ($hiringStage) {
            $this->whereClause('review', $hiringStage, "=");
        }
    }
}
