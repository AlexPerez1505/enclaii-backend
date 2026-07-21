<?php

namespace App\Http\Controllers\CustomerSuccess;

use App\Http\Controllers\Controller;
use App\Mail\TicketResuelto;
use App\Models\Notification;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

    public function resolveForm(Ticket $ticket): Response
    {
        if (in_array($ticket->status, ['nuevo', 'abierto'])) {
            $ticket->update(['status' => 'en_proceso']);
        }

        $ticket->load('user');
        return response()
            ->view('customer-success.tickets.resolve', compact('ticket'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function resolve(Request $request, Ticket $ticket): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:resuelto,cerrado'],
            'resolution_type' => ['required', 'string', 'in:problema_corregido,configuracion_realizada,error_usuario,capacitacion,incidencia_externa,otro'],
            'resolution_type_other' => ['nullable', 'string', 'max:255'],
            'resolution_summary' => ['required', 'string', 'max:2000'],
            'evidence' => ['nullable', 'array'],
            'evidence.*' => ['file', 'max:10240'],
            'remove_evidence' => ['nullable', 'string'],
            'notify_web' => ['nullable', 'boolean'],
            'notify_email' => ['nullable', 'boolean'],
        ]);

        $resolutionType = $validated['resolution_type'] === 'otro'
            ? ($validated['resolution_type_other'] ?? 'otro')
            : $validated['resolution_type'];

        $evidencePaths = $ticket->evidence_paths ?? [];
        $removedPaths = json_decode($validated['remove_evidence'] ?? '[]', true) ?? [];
        if (is_array($removedPaths) && count($removedPaths)) {
            $evidencePaths = array_values(array_filter($evidencePaths, function ($path) use ($removedPaths) {
                return !in_array($path, $removedPaths);
            }));
        }
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $evidencePaths[] = $file->store('tickets/evidence', 'public');
            }
        }

        $ticket->update([
            'status' => $validated['status'],
            'resolution_type' => $resolutionType,
            'resolution_summary' => $validated['resolution_summary'],
            'evidence_paths' => $evidencePaths,
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
        ]);

        $notifyWeb = (bool) ($validated['notify_web'] ?? false);
        $notifyEmail = (bool) ($validated['notify_email'] ?? false);

        if ($notifyWeb && $ticket->user) {
            Notification::create([
                'user_id' => $ticket->user_id,
                'tipo' => 'ticket_resuelto',
                'data' => [
                    'ticket_id' => $ticket->id,
                    'folio' => $ticket->operation_folio,
                    'subject' => $ticket->subject,
                    'resolution_type' => $ticket->resolution_type,
                    'resolution_summary' => $ticket->resolution_summary,
                    'resolver_name' => trim(($ticket->resolver?->name ?? '') . ' ' . ($ticket->resolver?->apellido_paterno ?? '')),
                    'ticket_url' => route('soporte.tickets.show', $ticket) . '?v=respuesta#respuesta',
                ],
                'read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($notifyEmail && $ticket->user?->email) {
            Mail::to($ticket->user->email)->send(new TicketResuelto($ticket->fresh(['user', 'resolver'])));
        }

        return response()->json([
            'ok' => true,
            'ticket' => $ticket->fresh(['user', 'resolver']),
        ]);
    }

    public function reopen(Ticket $ticket): JsonResponse
    {
        $ticket->update([
            'status' => 'en_proceso',
        ]);

        return response()->json([
            'ok' => true,
            'ticket' => $ticket->fresh(['user']),
            'redirect_url' => route('customer-success.tickets.show', $ticket),
        ]);
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
                'user_name' => trim(($ticket->user?->name ?? '').' '.($ticket->user?->apellido_paterno ?? '')),
                'user_email' => $ticket->user?->email,
            ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ])->all();

        Notification::insert($notifications);
    }
}
