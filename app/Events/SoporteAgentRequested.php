<?php

namespace App\Events;

use App\Models\AiConversation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SoporteAgentRequested implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly AiConversation $conversation,
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('soporte-agents')];
    }

    public function broadcastAs(): string
    {
        return 'agent.requested';
    }

    public function broadcastWith(): array
    {
        $user = $this->conversation->user;

        return [
            'conversation_id' => $this->conversation->id,
            'user_name'       => $user?->name.' '.($user?->apellido_paterno ?? ''),
            'user_email'      => $user?->email,
            'title'           => $this->conversation->title,
            'last_message_at' => $this->conversation->last_message_at?->toDateTimeString(),
        ];
    }
}
