<?php

namespace App\Events;

use App\Models\Estudio;
use App\Models\Notification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EstudioCompletado implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ?int $notificationId = null;

    public function __construct(
        public Estudio $estudio,
        public ?int $userId = null,
    ) {
        $this->userId = $userId ?? auth()->id();
    }

    public function broadcastOn(): array
    {
        try {
            $notification = Notification::create([
                'user_id' => $this->userId,
                'tipo' => 'estudio_completado',
                'data' => $this->broadcastWith(),
                'read' => false,
            ]);
            $this->notificationId = $notification->id;
        } catch (\Throwable $e) {
            // No bloquear el broadcast
        }

        return [
            new PrivateChannel('App.Models.User.' . $this->userId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->notificationId,
            'estudio_id' => $this->estudio->id,
            'paciente' => $this->estudio->paciente?->nombre_completo ?? $this->estudio->paciente_nombre,
            'estudio_tipo' => $this->estudio->tipo,
            'fecha' => optional($this->estudio->fecha)->format('d/m/Y'),
            'hora' => $this->estudio->hora_fin ? substr($this->estudio->hora_fin, 0, 5) : '',
            'tipo' => 'estudio_completado',
        ];
    }

    public function broadcastAs(): string
    {
        return 'estudio.completado';
    }
}
