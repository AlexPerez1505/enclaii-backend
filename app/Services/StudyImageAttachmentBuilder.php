<?php

namespace App\Services;

use App\Models\EstudioArchivo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class StudyImageAttachmentBuilder
{
    private const ALLOWED_EXTENSIONS = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
    ];

    /**
     * @param  array{archivo?: EstudioArchivo, name?: string}  $item
     * @return array{name: string, data: string, mime: string}|null
     */
    public function make(array $item): ?array
    {
        $archivo = $item['archivo'] ?? null;

        if (! $archivo instanceof EstudioArchivo || ! $archivo->path) {
            return null;
        }

        $disk = Storage::disk(media_disk());
        if (! $disk->exists($archivo->path)) {
            return null;
        }

        $sourceName = (string) ($item['name'] ?? $archivo->nombre_original ?? basename($archivo->path));
        $extension = $this->extension($sourceName, $archivo->path);
        $mime = self::ALLOWED_EXTENSIONS[$extension] ?? null;

        if (! $mime) {
            return null;
        }

        try {
            return [
                'name' => $this->filename($sourceName, $archivo, $extension === 'jpeg' ? 'jpg' : $extension),
                'data' => $disk->get($archivo->path),
                'mime' => $mime,
            ];
        } catch (Throwable) {
            return null;
        }
    }

    private function extension(string $sourceName, string $path): string
    {
        $extension = strtolower(pathinfo($sourceName, PATHINFO_EXTENSION));

        if ($extension === '') {
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        }

        return $extension;
    }

    private function filename(string $sourceName, EstudioArchivo $archivo, string $extension): string
    {
        $base = pathinfo($sourceName, PATHINFO_FILENAME);
        $base = Str::slug($base) ?: 'captura-'.$archivo->id;

        return $base.'.'.$extension;
    }
}
