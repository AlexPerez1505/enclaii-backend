<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class SignatureController extends Controller
{
    public function __construct(
        private readonly ActivityLogger $activity,
    ) {}

    public function show(Request $request): StreamedResponse
    {
        /** @var User $user */
        $user = $request->user();
        $path = $user->signature_path;

        abort_if(! $path || ! Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path, null, [
            'Cache-Control' => 'private, no-store, max-age=0',
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'signature' => [
                'required',
                'file',
                'image',
                'mimes:png,jpg,jpeg,webp',
                'max:2048',
                'dimensions:max_width=2400,max_height=1200',
            ],
        ]);

        /** @var User $user */
        $user = $request->user();
        $file = $validated['signature'];
        $extension = $file->extension() ?: 'png';
        $path = $file->storeAs(
            'signatures/'.$user->id,
            Str::uuid().'.'.$extension,
            'local',
        );

        if (! $path) {
            return response()->json([
                'message' => 'No se pudo guardar la firma.',
            ], 500);
        }

        $previousPath = $user->signature_path;

        try {
            $user->forceFill([
                'signature_path' => $path,
                'signature_updated_at' => now(),
            ])->save();
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw $exception;
        }

        if ($previousPath && $previousPath !== $path) {
            Storage::disk('local')->delete($previousPath);
        }
        $this->activity->record(
            $previousPath ? 'signature_updated' : 'signature_created',
            'signature',
            $previousPath ? 'Actualizó su firma digital' : 'Creó su firma digital',
            user: $user,
            request: $request,
        );

        return response()->json([
            'ok' => true,
            'message' => 'Firma actualizada correctamente.',
            'signature_url' => route('configuracion.signature.show').'?v='.$user->signature_updated_at->timestamp,
            'updated_at' => $user->signature_updated_at->toIso8601String(),
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $path = $user->signature_path;

        $user->forceFill([
            'signature_path' => null,
            'signature_updated_at' => null,
        ])->save();

        if ($path) {
            Storage::disk('local')->delete($path);
        }
        $this->activity->record(
            'signature_deleted',
            'signature',
            'Eliminó su firma digital',
            user: $user,
            request: $request,
        );

        return response()->json([
            'ok' => true,
            'message' => 'Firma eliminada correctamente.',
        ]);
    }
}
