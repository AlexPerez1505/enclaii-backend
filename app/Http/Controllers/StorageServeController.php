<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StorageServeController extends Controller
{
    public function show(string $path): StreamedResponse
    {
        $disk = Storage::disk('public');
        $exists = $disk->exists($path);

        // Logging temporal de diagnostico (LOG_LEVEL=error en produccion)
        Log::error('storage.fallback', [
            'path' => $path,
            'exists' => $exists,
            'root' => storage_path('app/public'),
            'full' => storage_path('app/public/'.$path),
            'realpath' => @realpath(storage_path('app/public/'.$path)),
            'cwd' => getcwd(),
        ]);

        if (! $exists) {
            Log::error('storage.fallback.404', ['reason' => 'file_not_found', 'path' => $path]);
            abort(404);
        }

        return new StreamedResponse(function () use ($disk, $path) {
            fpassthru($disk->readStream($path));
        }, 200, [
            'Content-Type' => $disk->mimeType($path),
            'Content-Length' => $disk->size($path),
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT',
        ]);
    }
}
