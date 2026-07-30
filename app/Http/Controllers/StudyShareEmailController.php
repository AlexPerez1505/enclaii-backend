<?php

namespace App\Http\Controllers;

use App\Mail\StudyShareMail;
use App\Models\Estudio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class StudyShareEmailController extends Controller
{
    public function store(Request $request, Estudio $estudio): JsonResponse
    {
        $validated = $request->validate([
            'recipients' => ['required', 'string', 'max:2000'],
            'subject' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $sender = $request->user();
        if (! $sender || ! filter_var($sender->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'message' => 'Tu cuenta no tiene un correo valido registrado.',
            ], 422);
        }

        $fromAddress = (string) config('mail.from.address');
        if (! filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'message' => 'Configura un correo Gmail valido para enviar correos reales.',
            ], 422);
        }

        $recipients = $this->parseRecipients($validated['recipients']);
        $invalidRecipients = $recipients
            ->reject(fn (string $email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->values();

        if ($recipients->isEmpty()) {
            return response()->json([
                'message' => 'Agrega al menos un correo de destino.',
            ], 422);
        }

        if ($invalidRecipients->isNotEmpty()) {
            return response()->json([
                'message' => 'Revisa estos correos: '.$invalidRecipients->implode(', '),
            ], 422);
        }

        if ($recipients->count() > 10) {
            return response()->json([
                'message' => 'Puedes enviar el estudio a maximo 10 destinatarios por envio.',
            ], 422);
        }

        $estudio->loadMissing(['paciente', 'archivos', 'reportes.usuario']);

        $imagenes = $this->shareableFiles($estudio, 'imagen');
        $videos = $this->shareableFiles($estudio, 'video');
        $reportes = $estudio->reportes
            ->sortByDesc('created_at')
            ->values();

        if ($imagenes->isEmpty() && $videos->isEmpty() && $reportes->isEmpty()) {
            return response()->json([
                'message' => 'Este estudio no tiene reportes, capturas ni videos para enviar.',
            ], 422);
        }

        try {
            Mail::to($recipients->all())->send(new StudyShareMail(
                estudio: $estudio,
                sender: $sender,
                subjectLine: $validated['subject'],
                messageBody: $validated['message'],
                imagenes: $imagenes,
                videos: $videos,
                reportes: $reportes,
            ));
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'No se pudo enviar el correo real en este momento.',
            ], 502);
        }

        return response()->json([
            'message' => 'Estudio enviado correctamente.',
            'sent_to' => $recipients->all(),
        ]);
    }

    private function parseRecipients(string $rawRecipients): Collection
    {
        return collect(preg_split('/[;,\s]+/', $rawRecipients) ?: [])
            ->map(fn (string $email) => trim($email))
            ->filter()
            ->unique(fn (string $email) => Str::lower($email))
            ->values();
    }

    private function shareableFiles(Estudio $estudio, string $type): Collection
    {
        return $estudio->archivos
            ->where('tipo', $type)
            ->filter(fn ($archivo) => $archivo->path && media_exists($archivo->path))
            ->sortByDesc('capturado_en')
            ->values()
            ->map(fn ($archivo) => [
                'archivo' => $archivo,
                'name' => $archivo->nombre_original ?: basename((string) $archivo->path),
                'url' => media_url($archivo->path, 60 * 24 * 7),
                'capturado_en' => $archivo->capturado_en,
            ]);
    }
}
