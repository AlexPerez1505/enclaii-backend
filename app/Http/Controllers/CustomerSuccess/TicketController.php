<?php

namespace App\Http\Controllers\CustomerSuccess;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(): View
    {
        $tickets = Ticket::with('user')
            ->orderByDesc('created_at')
            ->paginate(25);

        $stats = [
            'total' => Ticket::count(),
            'nuevos' => Ticket::where('status', 'nuevo')->count(),
            'en_curso' => Ticket::where('status', 'en_proceso')->count(),
            'cerrados' => Ticket::where('status', 'cerrado')->count(),
        ];

        return view('customer-success.tickets.index', compact('tickets', 'stats'));
    }

    public function poll(Request $request): JsonResponse
    {
        $tickets = Ticket::with('user')
            ->orderByDesc('created_at')
            ->limit(25)
            ->get()
            ->map(fn(Ticket $t) => [
                'id' => $t->id,
                'folio' => $t->operation_folio,
                'user_name' => trim(($t->user?->name ?? '?') . ' ' . ($t->user?->apellido_paterno ?? '')),
                'user_email' => $t->user?->email ?? '',
                'user_initials' => mb_strtoupper(mb_substr($t->user?->name ?? '?', 0, 2)),
                'category' => $t->category,
                'subject' => $t->subject,
                'status' => $t->status,
                'status_label' => ucfirst(str_replace('_', ' ', $t->status)),
                'created_at' => $t->created_at?->format('d/m/Y H:i'),
                'url' => route('customer-success.tickets.show', $t),
            ]);

        $stats = [
            'total' => Ticket::count(),
            'nuevos' => Ticket::where('status', 'nuevo')->count(),
            'en_curso' => Ticket::where('status', 'en_proceso')->count(),
            'cerrados' => Ticket::where('status', 'cerrado')->count(),
        ];

        return response()->json(['tickets' => $tickets, 'stats' => $stats]);
    }

    public function show(Ticket $ticket): View
    {
        if ($ticket->status === 'nuevo') {
            $ticket->update(['status' => 'abierto']);
        }

        $ticket->load(['user', 'resolver']);

        $attachmentSize = null;
        if ($ticket->attachment_path) {
            try {
                $bytes = Storage::disk('public')->size($ticket->attachment_path);
                $attachmentSize = $this->humanFileSize($bytes);
            } catch (\Throwable) {
                $attachmentSize = null;
            }
        }

        return view('customer-success.tickets.show', compact('ticket', 'attachmentSize'));
    }

    private function humanFileSize(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }

    public function update(Request $request, Ticket $ticket): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['nullable', 'string', 'in:nuevo,abierto,en_proceso,respondido,cerrado'],
            'priority' => ['nullable', 'string', 'in:baja,media,alta,urgente'],
        ]);

        $data = array_filter($validated, fn ($value) => $value !== null);

        if (! empty($data)) {
            $ticket->update($data);
        }

        return response()->json([
            'ok' => true,
            'ticket' => $ticket->fresh(),
        ]);
    }

    public function resolveForm(Ticket $ticket): View
    {
        if (in_array($ticket->status, ['nuevo', 'abierto'])) {
            $ticket->update(['status' => 'en_proceso']);
        }

        $ticket->load('user');
        return view('customer-success.tickets.resolve', compact('ticket'));
    }

    public function resolve(Request $request, Ticket $ticket): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:resuelto,cerrado'],
            'resolution_type' => ['required', 'string', 'in:problema_corregido,configuracion_realizada,error_usuario,capacitacion,incidencia_externa,otro'],
            'resolution_summary' => ['required', 'string', 'max:2000'],
            'send_message' => ['nullable', 'boolean'],
            'client_message' => ['nullable', 'required_if:send_message,true', 'string', 'max:2000'],
            'evidence' => ['nullable', 'file', 'max:10240'],
        ]);

        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')->store('tickets/evidence', 'public');
        }

        $ticket->update([
            'status' => $validated['status'],
            'resolution_type' => $validated['resolution_type'],
            'resolution_summary' => $validated['resolution_summary'],
            'client_message' => $validated['client_message'] ?? null,
            'evidence_path' => $evidencePath,
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
        ]);

        if (! empty($validated['send_message']) && ! empty($validated['client_message']) && $ticket->user_id) {
            Notification::create([
                'user_id' => $ticket->user_id,
                'tipo' => 'ticket',
                'data' => json_encode([
                    'tipo' => 'ticket',
                    'titulo' => 'Tu ticket fue resuelto',
                    'folio' => $ticket->operation_folio,
                    'subject' => $ticket->subject,
                    'message' => $validated['client_message'],
                ]),
                'read' => false,
            ]);
        }

        return response()->json([
            'ok' => true,
            'ticket' => $ticket->fresh(['user', 'resolver']),
        ]);
    }

    public function reopen(Ticket $ticket): JsonResponse
    {
        $ticket->update([
            'status' => 'abierto',
            'resolution_type' => null,
            'resolution_summary' => null,
            'client_message' => null,
            'evidence_path' => null,
            'resolved_by' => null,
            'resolved_at' => null,
        ]);

        return response()->json([
            'ok' => true,
            'ticket' => $ticket->fresh(),
        ]);
    }
}
