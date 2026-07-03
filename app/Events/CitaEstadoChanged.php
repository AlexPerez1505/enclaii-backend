<?php

namespace App\Events;

use App\Models\Cita;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CitaEstadoChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Cita $cita,
        public string $estadoAnterior,
        public string $estadoNuevo,
        public string $tipo = 'estado',
        public ?int $userId = null,
    ) {
        $this->userId = $userId ?? auth()->id();
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.' . $this->userId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'cita_id'          => $this->cita->id,
            'paciente'         => $this->cita->paciente?->nombre_completo ?? $this->cita->paciente_nombre,
            'estado_anterior'  => $this->estadoAnterior,
            'estado_nuevo'     => $this->estadoNuevo,
            'estado_nuevo_texto' => match($this->estadoNuevo) {
                'completado' => 'Completado',
                'en_espera'  => 'En espera',
                'cancelado'  => 'Cancelado',
                'eliminada'  => 'Eliminada',
                default      => 'Próximo',
            },
            'fecha'            => optional($this->cita->fecha)->format('d/m/Y'),
            'hora'             => substr($this->cita->hora, 0, 5),
            'tipo'             => $this->tipo,
        ];
    }

    public function broadcastAs(): string
    {
        return 'cita.estado-cambio';
    }
}
