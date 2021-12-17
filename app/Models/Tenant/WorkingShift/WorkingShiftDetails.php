<?php

namespace App\Models\Tenant\WorkingShift;

use App\Helpers\Core\Traits\Memoization;
use App\Models\Tenant\TenantModel;
use App\Models\Tenant\WorkingShift\Relationship\WorkingShiftDetailsRelationship;
use Illuminate\Support\Carbon;

class WorkingShiftDetails extends TenantModel
{
    use WorkingShiftDetailsRelationship;
    use Memoization;

    protected $fillable = [
        'weekday', 'working_shift_id', 'is_weekend', 'start_at', 'end_at'
    ];


    public function getWorkingHourInSeconds()
    {
        return $this->memoize('details-total-work-hour-'.$this->id, function () {
            $end_at = Carbon::parse($this->end_at);
            $start_at = Carbon::parse($this->start_at);

            if ($end_at->isAfter($start_at)) {
                return $end_at->diffInSeconds($start_at);
            }

            $start_at = Carbon::parse(Carbon::now()->format('Y-m-d')." ".$this->start_at);
            $end_at = Carbon::parse(Carbon::now()->addDay()->format('Y-m-d')." ".$this->end_at);

            return $end_at->diffInSeconds($start_at);
        });
    }

}
