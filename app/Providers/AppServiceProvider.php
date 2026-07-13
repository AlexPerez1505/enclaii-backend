<?php

namespace App\Providers;

use App\Models\Anuncio;
use App\Models\Estudio;
use App\Models\EstudioArchivo;
use App\Observers\AnuncioObserver;
use App\Observers\EstudioObserver;
use App\Observers\EstudioArchivoObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Estudio::observe(EstudioObserver::class);
        EstudioArchivo::observe(EstudioArchivoObserver::class);
        Anuncio::observe(AnuncioObserver::class);

        View::addLocation(resource_path('views-Tec'));
    }
}
