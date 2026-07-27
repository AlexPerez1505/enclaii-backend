<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('anuncios:publicar-programados')->everyMinute();
Schedule::command('desktop-app:notificar-actualizacion')->everyFiveMinutes();
