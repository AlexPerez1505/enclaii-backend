<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'priority' => ['required', 'string', 'in:alta,media,baja'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:4000'],
            'business_name' => ['nullable', 'string', 'max:500'],
            'operation_folio' => ['nullable', 'string', 'max:500'],
            'concepts' => ['nullable', 'string', 'max:2000'],
            'totals' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'attachment' => ['nullable', 'file', 'max:10240'],
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('tickets', 'public');
        }

        $ticket = Ticket::create([
            'user_id' => auth()->id(),
            'category' => $validated['category'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'business_name' => $validated['business_name'] ?? null,
            'operation_folio' => $validated['operation_folio'] ?? null,
            'concepts' => $validated['concepts'] ?? null,
            'payment_method' => $validated['payment_method'] ?? null,
            'attachment_path' => $attachmentPath,
            'status' => 'abierto',
        ]);

        return response()->json([
            'ok' => true,
            'ticket' => $ticket,
        ], 201);
    }
}
