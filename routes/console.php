<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// DISABLED: Birthday bonuses - uncomment when ready
// Schedule birthday bonuses to run daily at 9 AM
// Schedule::command('loyalty:birthday-bonuses')->dailyAt('09:00');

// Reset birthday bonus flags on January 1st each year
// Schedule::command('loyalty:reset-birthday-bonuses')->yearlyOn(1, 1, '00:01');
