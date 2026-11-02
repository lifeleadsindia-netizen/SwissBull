<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:sync-member-staking-status')->daily();
Schedule::command('app:daily-income-dis')->daily();
Schedule::command('app:team-trading-profit')->everyFiveMinutes();
Schedule::command('app:daily-team-investment-share')->daily();
Schedule::command('app:partnership-income-dis')->daily();
Schedule::command('app:daily-part-team-biz-update')->daily();
Schedule::command('app:hero-of-the-month-dis')->monthlyOn(1, '00:05');
Schedule::command('app:monthly-direct-biz-update')->monthlyOn(1, '00:05');
