<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function tickets(): View
    {
        $ticketQuery = Ticket::where('user_id', auth()->id())
            ->orderByDesc('updated_at');

        $activeTickets = (clone $ticketQuery)
            ->whereNotIn('status', ['respondido', 'resuelto', 'cerrado'])
            ->get();

        $answeredTickets = (clone $ticketQuery)
            ->whereIn('status', ['respondido', 'resuelto', 'cerrado'])
            ->get();

        return view('soporte.tickets', compact('activeTickets', 'answeredTickets'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->merge(array_filter([
            'payment_method' => $request->input('payment_method') === '' ? null : $request->input('payment_method'),
        ], fn($v) => $v !== null));

        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:4000'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'attachment' => ['nullable', 'file', 'max:10240', 'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,zip'],
        ]);

        $user = $request->user();

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = media_store($request->file('attachment'), 'tickets');
        }

        $businessName = implode(' — ', array_filter([
            $user->clinica_nombre,
            $user->razon_social,
            $user->rfc,
        ]));

        // Reabrir ticket reciente resuelto si el usuario envía el mismo asunto/categoría en menos de 24h
        $latestResolved = Ticket::where('user_id', $user->id)
            ->whereIn('status', ['resuelto', 'cerrado'])
            ->where('resolved_at', '>=', now()->subHours(24))
            ->latest('resolved_at')
            ->first();

        if (
            $latestResolved &&
            $latestResolved->category === $validated['category'] &&
            mb_strtolower(trim($latestResolved->subject)) === mb_strtolower(trim($validated['subject']))
        ) {
            $latestResolved->update([
                'status' => 'abierto',
                'description' => $latestResolved->description . "\n\n--- Reapertura ---\n" . $validated['description'],
                'payment_method' => $validated['payment_method'] ?? $latestResolved->payment_method,
                'attachment_path' => $attachmentPath ?: $latestResolved->attachment_path,
                'resolved_by' => null,
                'resolved_at' => null,
                'resolution_type' => null,
                'resolution_summary' => null,
                'client_message' => null,
                'evidence_path' => null,
            ]);

            $this->notifyCustomerSuccess($latestResolved);

            return response()->json([
                'ok' => true,
                'ticket' => $latestResolved->fresh(),
                'reopened' => true,
            ], 200);
        }

        $ticket = Ticket::create([
            'user_id' => $user->id,
            'category' => $validated['category'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'priority' => 'media',
            'business_name' => $businessName ?: null,
            'operation_folio' => $this->generarFolioTicket(),
            'operation_datetime' => now(),
            'payment_method' => $validated['payment_method'] ?? null,
            'attachment_path' => $attachmentPath,
            'status' => 'nuevo',
        ]);

        $this->notifyCustomerSuccess($ticket);

        return response()->json([
            'ok' => true,
            'ticket' => $ticket,
        ], 201);
    }

    private function generarFolioTicket(): string
    {
        $ultimoId = (int) Ticket::max('id') + 1;

        do {
            $folio = 'T-' . str_pad((string) $ultimoId, 4, '0', STR_PAD_LEFT);
            $ultimoId++;
        } while (Ticket::where('operation_folio', $folio)->exists());

        return $folio;
    }

    private function notifyCustomerSuccess(Ticket $ticket): void
    {
        $customerSuccessUsers = User::role(['Customer Success'])->get();

        if ($customerSuccessUsers->isEmpty()) {
            return;
        }

        $notifications = $customerSuccessUsers->map(fn (User $user) => [
            'user_id' => $user->id,
            'tipo' => 'ticket',
            'data' => json_encode([
                'ticket_id' => $ticket->id,
                'folio' => $ticket->operation_folio,
                'subject' => $ticket->subject,
                'category' => $ticket->category,
                'user_name' => $ticket->user?->name.' '.($ticket->user?->apellido_paterno ?? ''),
                'user_email' => $ticket->user?->email,
            ]),
            'read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ])->all();

        Notification::insert($notifications);
    }
}
