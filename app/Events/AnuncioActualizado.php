<?php

namespace App\Events;

use App\Models\Anuncio;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnuncioActualizado implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Anuncio $anuncio,
        public readonly array $targetUserIds,
    ) {}

    public function broadcastOn(): array
    {
        return array_map(
            fn (int $userId) => new PrivateChannel('App.Models.User.' . $userId),
            $this->targetUserIds
        );
    }

    public function broadcastAs(): string
    {
        return 'anuncio.actualizado';
    }

    public function broadcastWith(): array
    {
        return [
            'anuncio_id' => $this->anuncio->id,
            'titulo'     => $this->anuncio->titulo,
            'message'    => 'Anuncio actualizado: ' . $this->anuncio->titulo,
            'categoria'  => $this->anuncio->tipo,
        ];
    }
}
