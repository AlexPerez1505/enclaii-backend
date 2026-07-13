<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
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
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:4000'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'attachment' => ['nullable', 'file', 'max:10240'],
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
            'status' => 'abierto',
        ]);

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
}
