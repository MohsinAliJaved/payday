<?php

namespace App\Jobs\Tenant;

use App\Models\Tenant\Holiday\Holiday;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RenewHolidayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $holidays = Holiday::query()->where('repeats_annually', 1)
            ->whereYear('start_date', now()->year)->get();

        $holidays->map(function ($holiday){
            $newHoliday = $holiday->replicate()->fill([
                'start_date' => Carbon::parse($holiday->start_date)->addYear(),
                'end_date' => Carbon::parse($holiday->end_date)->addYear(),
            ]);
            $newHoliday->save();
            $newHoliday->departments()->sync($holiday->departments);
        });
    }
}
