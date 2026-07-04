<?php

namespace App\Observers;

use App\Models\Estudio;
use Illuminate\Support\Facades\Storage;

class EstudioObserver
{
    /**
     * Handle the Estudio "deleting" event.
     */
    public function deleting(Estudio $estudio): void
    {
        // Elimina el archivo de video asociado al estudio si existe.
        if ($estudio->video_path && Storage::disk('public')->exists($estudio->video_path)) {
            Storage::disk('public')->delete($estudio->video_path);
        }
    }
}
