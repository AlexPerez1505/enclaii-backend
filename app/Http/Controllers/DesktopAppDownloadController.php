<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DesktopAppDownloadController extends Controller
{
    private const INSTALLER_PATH = 'windows/stable/ENCLAII-Setup.msi';
    private const DOWNLOAD_NAME = 'ENCLAII-Setup.msi';

    public function __invoke(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->subscribed(), 403);

        $disk = Storage::disk('downloads');

        abort_unless($disk->exists(self::INSTALLER_PATH), 404, 'El instalador no está disponible.');

        $ttl = max(1, (int) config('filesystems.downloads_url_ttl', 10));

        $url = $disk->temporaryUrl(
            self::INSTALLER_PATH,
            now()->addMinutes($ttl),
            [
                'ResponseContentDisposition' => 'attachment; filename="'.self::DOWNLOAD_NAME.'"; filename*=UTF-8\'\''.rawurlencode(self::DOWNLOAD_NAME),
            ],
        );

        return redirect()->away($url);
    }
}
