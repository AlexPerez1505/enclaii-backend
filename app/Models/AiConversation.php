<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiConversation extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'status',
        'mode',
        'agent_id',
        'last_message_at',
        'closed_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function isBot(): bool
    {
        return $this->mode === 'bot' || $this->mode === null;
    }

    public function isPendingAgent(): bool
    {
        return $this->mode === 'pending_agent';
    }

    public function isWithAgent(): bool
    {
        return $this->mode === 'with_agent';
    }

    public function requestAgent(): void
    {
        $this->update(['mode' => 'pending_agent']);
    }

    public function assignAgent(int $agentId): void
    {
        $this->update([
            'mode' => 'with_agent',
            'agent_id' => $agentId,
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(AiMessage::class)->orderBy('id');
    }

    public function firstMessage()
    {
        return $this->hasOne(AiMessage::class)->oldestOfMany();
    }

    public function latestMessage()
    {
        return $this->hasOne(AiMessage::class)->latestOfMany();
    }
}