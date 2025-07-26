<?php

use App\Jobs\Deputy\SyncAllDeputiesJob;
use App\Jobs\DeputyExpense\SyncAllDeputiesExpensesJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new SyncAllDeputiesJob())->hourly()->timezone('America/Fortaleza');
Schedule::job(new SyncAllDeputiesExpensesJob())->everyFifteenMinutes()->timezone('America/Fortaleza');
