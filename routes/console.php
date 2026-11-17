<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Console Tasks (Feature R8: Automated Daily Expiry & Low-Stock Sweeps)
|--------------------------------------------------------------------------
*/
Schedule::command('blood:check-alerts --threshold=5')
    ->dailyAt('00:00')
    ->withoutOverlapping()
    ->runInBackground();
