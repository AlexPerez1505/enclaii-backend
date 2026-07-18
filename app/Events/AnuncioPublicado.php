<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnuncioPublicado implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly \App\Models\Anuncio $anuncio,
        public readonly array $targetUserIds,
    ) {
        //
    }

    public function broadcastOn(): array
    {
        return array_map(
            fn (int $userId) => new PrivateChannel('App.Models.User.' . $userId),
            $this->targetUserIds
        );
    }

    public function broadcastAs(): string
    {
        return 'anuncio.publicado';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->anuncio->id,
            'tipo' => 'anuncio',
            'titulo' => $this->anuncio->titulo,
            'categoria' => $this->anuncio->tipo,
            'message' => 'Se publicó un nuevo anuncio: ' . $this->anuncio->titulo,
            'created_at' => $this->anuncio->created_at?->toDateTimeString(),
            'read' => false,
        ];
    }
}
