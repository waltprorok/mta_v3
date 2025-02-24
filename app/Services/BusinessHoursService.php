<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class BusinessHoursService
{
    /**
     * @return array
     */
    public function getSelectHours(): array
    {
        $startPeriod = Carbon::parse('8:00');
        $endPeriod = Carbon::parse('22:00');

        $period = CarbonPeriod::create($startPeriod, '30 minutes', $endPeriod);

        $hours = [];

        foreach ($period as $date) {
            $hours[] = $date->format('H:i:s');
        }

        return $hours;
    }

    /**
     * @param $hours
     * @return float|int
     */
    public function getTotalHours($hours): float|int
    {
        $totalHours = 0;

        foreach ($hours as $hour) {
            if ($hour->active) {
                $totalHours += Carbon::createFromTimestamp(strtotime($hour->open_time))->diffInMinutes($hour->close_time);
            }
        }

        return $totalHours / 60;
    }
}
