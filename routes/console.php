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

Schedule::command('app:daily-income-dis')->everyMinute()->timezone('UTC');
Schedule::command('app:single-leg-income')->everyThreeMinutes()->timezone('UTC');
Schedule::command('app:daily-part-team-biz-update')->daily()->timezone('UTC');
Schedule::command('app:partnership-income-dis')->daily()->timezone('UTC');
