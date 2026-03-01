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
        return collect(
            CarbonPeriod::create('08:00', '30 minutes', '22:00')
        )
            ->map(fn($dt) => $dt->format('H:i:s'))
            ->values()
            ->all();
    }

    /**
     * @param $hours
     * @return float
     */
    public function getTotalHours($hours): float
    {
        $totalMinutes = $hours
            ->where('active', true)
            ->sum(function ($hour) {
                return Carbon::parse($hour->open_time)
                    ->diffInMinutes(Carbon::parse($hour->close_time));
            });

        return $totalMinutes / 60;
    }
}
