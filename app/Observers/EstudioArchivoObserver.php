<?php

namespace App\Observers;

use App\Models\EstudioArchivo;
class EstudioArchivoObserver
{
    /**
     * Handle the EstudioArchivo "deleted" event.
     */
    public function deleted(EstudioArchivo $archivo): void
    {
        media_delete($archivo->path);
    }

    /**
     * Handle the EstudioArchivo "forceDeleted" event.
     */
    public function forceDeleted(EstudioArchivo $archivo): void
    {
        $this->deleted($archivo);
    }
}
