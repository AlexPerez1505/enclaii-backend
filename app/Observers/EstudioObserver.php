<?php

namespace App\Observers;

use App\Models\Estudio;
class EstudioObserver
{
    /**
     * Handle the Estudio "deleting" event.
     */
    public function deleting(Estudio $estudio): void
    {
        media_delete($estudio->video_path);
    }
}
