<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\CustomerSuccess\AnuncioController;
use App\Models\Anuncio;
use Illuminate\Console\Command;

class PublicarAnunciosProgramados extends Command
{
    protected $signature   = 'anuncios:publicar-programados';
    protected $description = 'Publica los anuncios cuya fecha de publicación ya llegó';

    public function handle(AnuncioController $controller): void
    {
        $anuncios = Anuncio::where('activo', false)
            ->whereNotNull('fecha_publicacion')
            ->where('fecha_publicacion', '<=', now())
            ->get();

        foreach ($anuncios as $anuncio) {
            $anuncio->update(['activo' => true]);
            $controller->publishNow($anuncio);
            $this->info("Publicado: [{$anuncio->id}] {$anuncio->titulo}");
        }

        if ($anuncios->isEmpty()) {
            $this->info('No hay anuncios programados pendientes.');
        }
    }
}
