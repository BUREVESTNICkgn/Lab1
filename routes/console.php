<?php

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('Some inspiring quote...');
})->purpose('Display an inspiring quote');

//ConsoleKernel::schedule(function ($schedule) {
   // $schedule->job(new \App\Jobs\ExpireProducts)->daily();  // Твой код для ежедневного удаления просроченных товаров
//});