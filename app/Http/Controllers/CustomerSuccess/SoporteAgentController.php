<?php

namespace App\Http\Controllers\CustomerSuccess;

use App\Http\Controllers\Controller;
use App\Models\AiConversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SoporteAgentController extends Controller
{
    public function index(): View
    {
        $pending = AiConversation::where('type', 'soporte')
            ->where('mode', 'pending_agent')
            ->with(['user', 'latestMessage'])
            ->latest('last_message_at')
            ->get();

        $active = AiConversation::where('type', 'soporte')
            ->where('mode', 'with_agent')
            ->where('agent_id', auth()->id())
            ->with(['user', 'latestMessage'])
            ->latest('last_message_at')
            ->get();

        return view('customer-success.soporte.index', compact('pending', 'active'));
    }

    public function show(AiConversation $conversation): View
    {
        abort_unless(
            $conversation->type === 'soporte' &&
            in_array($conversation->mode, ['pending_agent', 'with_agent']),
            404
        );

        $messages = $conversation->messages()->get();

        return view('customer-success.soporte.chat', compact('conversation', 'messages'));
    }

    public function take(AiConversation $conversation): JsonResponse
    {
        abort_unless($conversation->type === 'soporte', 404);

        if ($conversation->isWithAgent()) {
            return response()->json(['message' => 'Esta conversación ya tiene un agente asignado.'], 409);
        }

        $conversation->assignAgent(auth()->id());

        $conversation->messages()->create([
            'role'    => 'system',
            'content' => 'Un agente se ha conectado y te atenderá en breve.',
        ]);
        $conversation->update(['last_message_at' => now()]);

        return response()->json(['ok' => true, 'mode' => $conversation->mode]);
    }

    public function reply(Request $request, AiConversation $conversation): JsonResponse
    {
        abort_unless(
            $conversation->type === 'soporte' && $conversation->isWithAgent(),
            403
        );

        $data = $request->validate(['message' => 'required|string|max:4000']);

        $message = $conversation->messages()->create([
            'role'    => 'agent',
            'content' => $data['message'],
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json(['ok' => true, 'message_id' => $message->id]);
    }

    public function close(AiConversation $conversation): JsonResponse
    {
        abort_unless($conversation->type === 'soporte', 404);

        $conversation->messages()->create([
            'role'    => 'system',
            'content' => 'El agente ha marcado esta conversación como resuelta. ¡Gracias por contactarnos!',
        ]);

        $conversation->update([
            'status'    => 'closed',
            'mode'      => 'bot',
            'closed_at' => now(),
            'last_message_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }

    public function poll(Request $request, AiConversation $conversation): JsonResponse
    {
        abort_unless($conversation->type === 'soporte', 404);

        $lastId = (int) $request->query('last_id', 0);

        $newMessages = $conversation->messages()
            ->where('id', '>', $lastId)
            ->whereIn('role', ['user'])
            ->get()
            ->map(fn ($m) => [
                'id'         => $m->id,
                'role'       => $m->role,
                'content'    => $m->content,
                'created_at' => $m->created_at?->toDateTimeString(),
            ]);

        return response()->json([
            'ok'       => true,
            'messages' => $newMessages,
        ]);
    }

    public function pending(): JsonResponse
    {
        $conversations = AiConversation::where('type', 'soporte')
            ->where('mode', 'pending_agent')
            ->with(['user', 'latestMessage'])
            ->latest('last_message_at')
            ->get()
            ->map(fn ($c) => [
                'id'             => $c->id,
                'title'          => $c->title,
                'user_name'      => $c->user?->name.' '.($c->user?->apellido_paterno ?? ''),
                'user_email'     => $c->user?->email,
                'last_message'   => $c->latestMessage?->content,
                'last_message_at'=> $c->last_message_at?->diffForHumans(),
            ]);

        return response()->json(['ok' => true, 'conversations' => $conversations]);
    }
}
