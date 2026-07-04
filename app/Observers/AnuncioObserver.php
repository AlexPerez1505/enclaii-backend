<?php

namespace App\Observers;

use App\Models\Anuncio;
use App\Models\CustomerSuccessAuditLog;
use Illuminate\Support\Facades\Request;

class AnuncioObserver
{
    /**
     * Handle the Anuncio "created" event.
     */
    public function created(Anuncio $anuncio): void
    {
        $this->log($anuncio, 'created', null, $anuncio->getAttributes());
    }

    /**
     * Handle the Anuncio "updated" event.
     */
    public function updated(Anuncio $anuncio): void
    {
        $this->log($anuncio, 'updated', $anuncio->getOriginal(), $anuncio->getChanges());
    }

    /**
     * Handle the Anuncio "deleted" event.
     */
    public function deleted(Anuncio $anuncio): void
    {
        $this->log($anuncio, 'deleted', $anuncio->getAttributes(), null);
    }

    /**
     * Registra la acción en la tabla de auditoría.
     */
    private function log(Anuncio $anuncio, string $action, ?array $oldValues, ?array $newValues): void
    {
        CustomerSuccessAuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'entity_type' => Anuncio::class,
            'entity_id' => $anuncio->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
