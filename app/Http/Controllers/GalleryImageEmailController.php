<?php

namespace App\Http\Controllers;

use App\Mail\GalleryImageShareMail;
use App\Models\EstudioArchivo;
use App\Services\MailSenderResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class GalleryImageEmailController extends Controller
{
    public function store(Request $request, EstudioArchivo $archivo): JsonResponse
    {
        abort_unless($archivo->tipo === 'imagen', 404);

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

        $mailSender = app(MailSenderResolver::class);
        if (! $mailSender->applyToConfig()) {
            return response()->json([
                'message' => $mailSender->configurationMessage(),
            ], 422);
        }

        $recipients = collect(preg_split('/[;,\s]+/', $validated['recipients']) ?: [])
            ->map(fn (string $email) => trim($email))
            ->filter()
            ->unique(fn (string $email) => Str::lower($email))
            ->values();

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
                'message' => 'Puedes enviar la imagen a maximo 10 destinatarios por envio.',
            ], 422);
        }

        $archivo->loadMissing(['estudio.paciente']);

        abort_unless($archivo->path && media_exists($archivo->path), 404);

        $downloadName = $archivo->nombre_original ?: basename((string) $archivo->path);
        $imageUrl = media_url($archivo->path, 60 * 24 * 7);

        try {
            Mail::to($recipients->all())->send(new GalleryImageShareMail(
                archivo: $archivo,
                sender: $sender,
                subjectLine: $validated['subject'],
                messageBody: $validated['message'],
                imageUrl: $imageUrl,
                downloadName: $downloadName,
            ));
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => $mailSender->deliveryFailureMessage($exception),
            ], 502);
        }

        return response()->json([
            'message' => 'Correo enviado correctamente.',
            'sent_to' => $recipients->all(),
        ]);
    }
}
