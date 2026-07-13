<?php

namespace App\Events;

use App\Models\AiConversation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SoporteAgentReplied implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly AiConversation $conversation,
        public readonly array $message,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('soporte.'.$this->conversation->id)];
    }

    public function broadcastAs(): string
    {
        return 'agent.replied';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversation->id,
            'role'            => $this->message['role'],
            'content'         => $this->message['content'],
            'agent_name'      => $this->conversation->agent?->name,
        ];
    }
}
