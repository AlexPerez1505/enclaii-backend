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
        $tickets = Ticket::where('user_id', auth()->id())
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('soporte.tickets', compact('tickets'));
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
