<?php


namespace App\Filters\Tenant\JobPost;

use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class JobPostFilter extends FilterBuilder
{
    public function search($value = null)
    {
        $this->builder->where(function (Builder $builder) use ($value) {
            $builder->whereRaw("name LIKE ?", "%{$value}%");
        });
    }

    public function date($qry)
    {
        $date = json_decode(htmlspecialchars_decode(request()->get('applied_date_range')), true);

        $qry->when($date, function ($qry) use ($date) {
            return $qry->whereBetween(\DB::raw('DATE(created_at)'), array_values($date));
        });

        return $qry;
    }

    public function type($id)
    {
        $this->builder->where('job_type_id', $id);
    }

    public function status($id)
    {
        $this->builder->where('status_id', $id);
    }
}
