<?php


namespace App\Helpers\Traits;


use Carbon\CarbonInterval;
use DatePeriod;
use Illuminate\Support\Carbon;

trait DateRangeHelper
{
    private function today(): array
    {
        return [
            'name' => 'total',
            'range' => today(),
            'context' => 'Y'
        ];
    }
    private function last7Days(): array
    {
        return [
            'name' => 'last_7_days',
            'range' => [
                today()->subDays(7),
                now()
            ],
            'context' => 'D'
        ];
    }
    private function thisWeek(): array
    {
        return [
            'name' => 'this_week',
            'range' => [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ],
            'context' => 'D'
        ];
    }
    private function lastWeek(): array
    {
        return [
            'name' => 'last_week',
            'range' => [
                now()->subWeek()->startOfWeek(),
                now()->subWeek()->endOfWeek()
            ],
            'context' => 'D'
        ];
    }
    private function thisMonth(): array
    {
        return [
            'name' => 'this_month',
            'range' => [
                now()->startOfMonth(),
                now()->endOfMonth()
            ],
            'context' => 'Y-m-d'
        ];
    }
    private function lastMonth(): array
    {
        return [
            'name' => 'last_month',
            'range' => [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth()->lastOfMonth()
            ],
            'context' => 'Y-m-d'
        ];
    }

    private function thisYear(): array
    {
        return [
            'name' => 'this_year',
            'range' => [
                now()->startOfYear(),
                now()->endOfYear()
            ],
            'context' => 'M'
        ];
    }

    public function startAndEndOfMonth($year, $month)
    {
        return [
            Carbon::now()->setYear($year)->setMonth($month)->startOfMonth(),
            Carbon::now()->setYear($year)->setMonth($month)->endOfMonth(),
        ];
    }

    public function contexts(): array
    {
        return ['today', 'thisWeek', 'lastWeek', 'thisMonth', 'lastMonth', 'thisYear'];
    }

    public function dateRange(Carbon $from, Carbon $to, $inclusive = true): array
    {
        if ($from->gt($to)) {
            return [];
        }

        // Clone the date objects to avoid issues, then reset their time
        $from = $from->copy()->startOfDay();
        $to = $to->copy()->startOfDay();

        // Include the end date in the range
        if ($inclusive) {
            $to->addDay();
        }

        $step = CarbonInterval::day();
        $period = new DatePeriod($from, $step, $to);

        // Convert the DatePeriod into a plain array of Carbon objects
        $range = [];

        foreach ($period as $day) {
            $range[] = new Carbon($day);
        }

        return ! empty($range) ? $range : [];
    }

    public function getDateRange($within = null, $year = 0)
    {
        if (!$within) {
            return [];
        }

        $range = $this->getStartAndEndOf($within, $year);

        return count($range) == 1 ? $range : $this->dateRange($range[0], $range[1]);
    }

    public function getStartAndEndOf($within = null, $year = 0)
    {
        if ($within === 'today') {
            return [Carbon::now()];
        }

        if (in_array($within, $this->contexts())) {
            $range = $this->$within();

            return [$range['range'][0], $range['range'][1]];
        }

        $month = $this->startAndEndOfMonth($year, $within);

        return [$month[0], $month[1]];
    }

    public function convertRangesToStringFormat(array $ranges)
    {
        return array_map(
            fn(Carbon $carbon) => $carbon->format('Y-m-d'),
            $ranges
        );
    }

    public function convertSecondsToHoursMinutes($seconds)
    {
        $minutes = $seconds / 60;

        $hours = (int)($minutes / 60);

        $restMinutes = abs($minutes % 60);
        $restSecond = abs($seconds % 60);

        $restMinutes = strlen((string) $restMinutes) === 1 ? "0".$restMinutes : $restMinutes;
        $restSecond = strlen((string) $restSecond) === 1 ? "0".$restSecond : $restSecond;

        return $hours.":".$restMinutes.":".$restSecond;
    }

    public function convertSecondsToHours($seconds): int
    {
        $minutes = $seconds / 60;

        return (int)($minutes / 60);
    }

    public function yearRange($month, $year = null): array
    {
        $year = $year ?: Carbon::now()->year;
        $monthNumber = is_string($month) ? Carbon::parse($month)->month : $month;
        return [
            Carbon::now()->setYear($year)->setMonth($monthNumber)->startOfMonth(),
            Carbon::now()->setYear($year)->setMonth($monthNumber)->startOfMonth()
                ->addMonths(11)
                ->addDays(
                    Carbon::now()
                        ->setYear($year)
                        ->setMonth($monthNumber)
                        ->startOfMonth()
                        ->addMonths(11)
                        ->daysInMonth - 1
                )
        ];
    }

    public function fromWithinAndMonthNumberToRange($within = null, $month_number = null)
    {
        $month = $within ? $within : $month_number + 1;
        $ranges = $this->getStartAndEndOf($month, request()->get('year'));
        return count($ranges) === 1 ? [$ranges[0], $ranges[0]] : $ranges;
    }

    public function getDateDifferenceString($start, $end): string
    {
        $start = $start instanceof Carbon ? $start : Carbon::parse($start);
        $end = $end instanceof Carbon ? $end : Carbon::parse($end);
        $start_format = $start->day;
        $end_format = $end->format('d M,Y');
        if ($start->month != $end->month){
            $start_format = $start->format('d M');
        }
        return $start_format.'-'.$end_format;
    }
}
