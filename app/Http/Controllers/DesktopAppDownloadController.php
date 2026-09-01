<?php

namespace App\Http\Controllers;

use App\Support\DesktopAppRelease;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DesktopAppDownloadController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->subscribed(), 403);

        $disk = Storage::disk('downloads');
        $platform = (string) $request->route('platform', DesktopAppRelease::PLATFORM_WINDOWS);
        $release = DesktopAppRelease::forPlatform($platform);

        abort_unless($release !== [], 404, 'La descarga solicitada no esta disponible.');

        $installerPath = (string) $release['installer_path'];
        $downloadName = (string) $release['download_name'];

        abort_unless($installerPath !== '' && $disk->exists($installerPath), 404, 'El instalador no esta disponible.');

        $ttl = max(1, (int) config('filesystems.downloads_url_ttl', 10));

        $url = $disk->temporaryUrl(
            $installerPath,
            now()->addMinutes($ttl),
            [
                'ResponseContentDisposition' => 'attachment; filename="'.$downloadName.'"; filename*=UTF-8\'\''.rawurlencode($downloadName),
            ],
        );

        return redirect()->away($url);
    }
}
