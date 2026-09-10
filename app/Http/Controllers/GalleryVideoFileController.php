<?php

namespace App\Http\Controllers;

use App\Models\EstudioArchivo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GalleryVideoFileController extends Controller
{
    public function download($id): StreamedResponse|Response
    {
        $archivo = $this->findVideo($id);

        return $this->serveVideo(request(), $archivo, 'attachment');
    }

    public function stream(Request $request, $id): StreamedResponse|Response
    {
        $archivo = $this->findVideo($id);

        return $this->serveVideo($request, $archivo, 'inline');
    }

    private function findVideo($id): EstudioArchivo
    {
        return EstudioArchivo::where('tipo', 'video')->findOrFail($id);
    }

    private function serveVideo(Request $request, EstudioArchivo $archivo, string $disposition): StreamedResponse|Response
    {
        abort_unless($archivo->path && media_exists($archivo->path), 404);

        $disk = Storage::disk(media_disk());
        $filename = $this->filename($archivo);
        $size = (int) ($archivo->size_bytes ?: $disk->size($archivo->path));
        $mime = $archivo->mime_type ?: ($disk->mimeType($archivo->path) ?: 'application/octet-stream');
        $range = $this->parseRange($request->header('Range'), $size);

        if ($range === false) {
            return response('', 416, [
                'Accept-Ranges' => 'bytes',
                'Content-Range' => 'bytes */'.$size,
            ]);
        }

        [$start, $end] = $range ?? [0, max(0, $size - 1)];
        $length = $size > 0 ? $end - $start + 1 : 0;
        $status = $range ? 206 : 200;

        $headers = [
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'private, max-age=300',
            'Content-Disposition' => $this->contentDisposition($disposition, $filename),
            'Content-Length' => (string) $length,
            'Content-Type' => $mime,
            'X-Content-Type-Options' => 'nosniff',
        ];

        if ($range) {
            $headers['Content-Range'] = "bytes {$start}-{$end}/{$size}";
        }

        return new StreamedResponse(function () use ($disk, $archivo, $range, $start, $length) {
            if ($this->canReadS3Range($disk)) {
                $this->streamS3($disk, $archivo->path, $range);

                return;
            }

            $this->streamDisk($disk, $archivo->path, $start, $length);
        }, $status, $headers);
    }

    private function filename(EstudioArchivo $archivo): string
    {
        $filename = $archivo->nombre_original ?: basename((string) $archivo->path);
        $filename = preg_replace('/[\r\n"]+/', '', $filename) ?: 'video-'.$archivo->id;

        return $filename;
    }

    private function contentDisposition(string $disposition, string $filename): string
    {
        return $disposition.'; filename="'.$filename.'"; filename*=UTF-8\'\''.rawurlencode($filename);
    }

    private function parseRange(?string $header, int $size): array|false|null
    {
        if (! $header || $size <= 0) {
            return null;
        }

        if (! preg_match('/^bytes=(\d*)-(\d*)$/', trim($header), $matches)) {
            return false;
        }

        [$startRaw, $endRaw] = [$matches[1], $matches[2]];
        if ($startRaw === '' && $endRaw === '') {
            return false;
        }

        if ($startRaw === '') {
            $suffixLength = (int) $endRaw;
            if ($suffixLength <= 0) {
                return false;
            }

            $start = max(0, $size - $suffixLength);
            $end = $size - 1;
        } else {
            $start = (int) $startRaw;
            $end = $endRaw === '' ? $size - 1 : (int) $endRaw;
        }

        if ($start >= $size || $start > $end) {
            return false;
        }

        return [$start, min($end, $size - 1)];
    }

    private function canReadS3Range($disk): bool
    {
        return method_exists($disk, 'getClient') && method_exists($disk, 'getConfig');
    }

    private function streamS3($disk, string $path, ?array $range): void
    {
        $config = $disk->getConfig();
        $options = [
            'Bucket' => $config['bucket'],
            'Key' => ltrim($disk->path($path), '/'),
        ];

        if ($range) {
            [$start, $end] = $range;
            $options['Range'] = "bytes={$start}-{$end}";
        }

        $result = $disk->getClient()->getObject($options);
        $body = $result['Body'] ?? null;

        if ($body instanceof StreamInterface) {
            while (! $body->eof()) {
                echo $body->read(1024 * 1024);
                flush();
            }

            return;
        }

        echo (string) $body;
    }

    private function streamDisk($disk, string $path, int $start, int $length): void
    {
        $stream = $disk->readStream($path);
        if ($stream === false) {
            return;
        }

        try {
            if ($start > 0) {
                fseek($stream, $start);
            }

            $remaining = $length;
            while ($remaining > 0 && ! feof($stream)) {
                $chunkSize = min(1024 * 1024, $remaining);
                $buffer = fread($stream, $chunkSize);
                if ($buffer === false || $buffer === '') {
                    break;
                }

                echo $buffer;
                $remaining -= strlen($buffer);
                flush();
            }
        } finally {
            fclose($stream);
        }
    }
}
