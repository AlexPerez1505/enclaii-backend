<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CronController extends Controller
{
    public function run(Request $request): JsonResponse
    {
        $token = config('services.cron.secret');

        if (!$token || $request->header('X-Cron-Token') !== $token) {
            abort(403, 'Unauthorized');
        }

        Artisan::call('notificaciones:citas');
        $outputCitas = Artisan::output();

        Artisan::call('anuncios:publicar-programados');
        $outputAnuncios = Artisan::output();

        return response()->json([
            'ok'     => true,
            'output' => trim($outputCitas) . "\n" . trim($outputAnuncios),
        ]);
    }

    public function runAnuncios(Request $request): JsonResponse
    {
        $token = config('services.cron.secret');

        if (!$token || $request->header('X-Cron-Token') !== $token) {
            abort(403, 'Unauthorized');
        }

        Artisan::call('anuncios:publicar-programados');
        $output = Artisan::output();

        return response()->json([
            'ok'     => true,
            'output' => trim($output),
        ]);
    }
}
