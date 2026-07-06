<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\CustomerSuccess\AnuncioController;
use App\Models\Anuncio;
use Illuminate\Console\Command;

class PublicarAnunciosProgramados extends Command
{
    protected $signature = 'anuncios:publicar-programados';
    protected $description = 'Publica los anuncios cuya fecha de publicación programada ya llegó.';

    public function handle(AnuncioController $controller): int
    {
        $anuncios = Anuncio::where('activo', false)
            ->whereNotNull('fecha_publicacion')
            ->where('fecha_publicacion', '<=', now())
            ->get();

        if ($anuncios->isEmpty()) {
            $this->info('No hay anuncios programados para publicar.');
            return self::SUCCESS;
        }

        foreach ($anuncios as $anuncio) {
            $anuncio->update(['activo' => true]);
            $controller->publishNow($anuncio);
            $this->info("Anuncio publicado: {$anuncio->titulo}");
        }

        $this->info("Se publicaron {$anuncios->count()} anuncios.");
        return self::SUCCESS;
    }
}
