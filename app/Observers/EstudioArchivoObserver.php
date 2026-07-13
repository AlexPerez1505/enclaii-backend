<?php

namespace App\Observers;

use App\Models\EstudioArchivo;
use Illuminate\Support\Facades\Storage;

class EstudioArchivoObserver
{
    /**
     * Handle the EstudioArchivo "deleted" event.
     */
    public function deleted(EstudioArchivo $archivo): void
    {
        if ($archivo->path && Storage::disk('public')->exists($archivo->path)) {
            Storage::disk('public')->delete($archivo->path);
        }
    }

    /**
     * Handle the EstudioArchivo "forceDeleted" event.
     */
    public function forceDeleted(EstudioArchivo $archivo): void
    {
        $this->deleted($archivo);
    }
}
