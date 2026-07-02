<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrintTestController extends Controller
{
    public function show(Request $request): View
    {
        $validated = $request->validate([
            'page_size' => ['nullable', 'in:letter,a4'],
            'orientation' => ['nullable', 'in:portrait,landscape'],
            'mode' => ['nullable', 'in:preview,print,pdf'],
        ]);

        /** @var User $user */
        $user = $request->user();
        $pageSize = $validated['page_size'] ?? 'letter';
        $orientation = $validated['orientation'] ?? 'portrait';
        $mode = $validated['mode'] ?? 'preview';
        $showHeader = $request->boolean('show_header', true);
        $showLogo = $request->boolean('show_logo', true);
        $showSignature = $request->boolean('show_signature', true);
        $useColor = $request->boolean('use_color', true);
        $signatureData = null;

        if ($showSignature && $user->signature_path && Storage::disk('local')->exists($user->signature_path)) {
            $mime = Storage::disk('local')->mimeType($user->signature_path) ?: 'image/png';
            $signatureData = 'data:'.$mime.';base64,'.base64_encode(
                Storage::disk('local')->get($user->signature_path),
            );
        }

        return view('configuracion.print-test', compact(
            'user',
            'pageSize',
            'orientation',
            'mode',
            'showHeader',
            'showLogo',
            'showSignature',
            'useColor',
            'signatureData',
        ));
    }
}
