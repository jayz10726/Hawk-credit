<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;
Schedule::command('hawks:apply-penalties')->dailyAt('00:05');
Schedule::command('hawks:recalculate-scores')->dailyAt('01:00');
Schedule::command('hawks:send-due-reminders')->dailyAt('08:00');
Schedule::command('hawks:weekly-reports')->weeklyOn(1, '06:00');    
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
