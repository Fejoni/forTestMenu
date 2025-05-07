<?php

namespace App\Services\Menu;

use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

class MenuServices
{
    public function getDates(): array
    {
        App::setLocale('ru');
        Carbon::setLocale('ru');

        $period = CarbonPeriod::create(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());

        $dates = [];

        foreach ($period as $date) {
            $dates[] = mb_strtolower($date->isoFormat('dd DD.MM'));
        }

        return $dates;
    }
}
