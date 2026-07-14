<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('soporte-agents', function ($user) {
    return $user->hasRole('customer_success') || $user->hasRole('admin');
});

Broadcast::channel('soporte.{conversationId}', function ($user, $conversationId) {
    $conversation = \App\Models\AiConversation::find($conversationId);
    if (! $conversation) {
        return false;
    }
    return (int) $user->id === (int) $conversation->user_id
        || (int) $user->id === (int) $conversation->agent_id
        || $user->hasRole('customer_success');
});
