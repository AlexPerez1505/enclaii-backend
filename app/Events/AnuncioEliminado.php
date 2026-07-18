<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnuncioEliminado implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly int $anuncioId,
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
        return 'anuncio.eliminado';
    }

    public function broadcastWith(): array
    {
        return [
            'anuncio_id' => $this->anuncioId,
        ];
    }
}
