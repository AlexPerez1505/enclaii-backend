<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StorageServeController extends Controller
{
    public function show(string $path): StreamedResponse
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
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
