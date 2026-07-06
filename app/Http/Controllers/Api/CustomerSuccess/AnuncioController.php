<?php

namespace App\Http\Controllers\Api\CustomerSuccess;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerSuccess\StoreAnuncioRequest;
use App\Models\Anuncio;
use App\Models\Notification;
use App\Models\User;
use App\Services\HtmlPurifierService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnuncioController extends Controller
{
    /**
     * Mapea la categoría del anuncio al setting de pantalla del usuario.
     * Si el usuario tiene desactivado ese setting, no recibe la notificación.
     */
    private const CATEGORY_SETTING_MAP = [
        'anuncios_internos' => 'notif_new_studies_screen',
        'mejoras' => 'notif_updates_screen',
        'mantenimiento' => 'notif_maintenance_screen',
        'politicas' => 'notif_privacy_screen',
    ];

    public function __construct(
        private readonly HtmlPurifierService $purifier
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $anuncios = Anuncio::with('user')
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($anuncios);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnuncioRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $validated['contenido'] = $this->purifier->clean($validated['contenido']);
        $validated['user_id'] = $request->user()->id;

        $anuncio = Anuncio::create($validated);

        // Notificar a usuarios que tengan activada la categoría correspondiente
        $now = now();
        $settingKey = self::CATEGORY_SETTING_MAP[$anuncio->tipo] ?? null;

        $users = User::whereKeyNot($request->user()->id)
            ->get(['id', 'settings']);

        $notifications = $users
            ->filter(function ($user) use ($settingKey) {
                if ($settingKey === null) {
                    return true;
                }

                $settings = $user->resolvedSettings();

                return $settings[$settingKey] ?? true;
            })
            ->map(fn ($user) => [
                'user_id' => $user->id,
                'tipo' => 'anuncio',
                'data' => json_encode([
                    'anuncio_id' => $anuncio->id,
                    'titulo' => $anuncio->titulo,
                    'categoria' => $anuncio->tipo,
                    'message' => 'Se publicó un nuevo anuncio: ' . $anuncio->titulo,
                ]),
                'read' => false,
                'read_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();

        if (!empty($notifications)) {
            Notification::insert($notifications);
        }

        return response()->json($anuncio, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Anuncio $anuncio): JsonResponse
    {
        return response()->json($anuncio->load('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAnuncioRequest $request, Anuncio $anuncio): JsonResponse
    {
        $validated = $request->validated();
        $validated['contenido'] = $this->purifier->clean($validated['contenido']);

        $anuncio->update($validated);

        return response()->json($anuncio);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anuncio $anuncio): JsonResponse
    {
        $anuncio->delete();

        return response()->json(['message' => 'Anuncio eliminado correctamente.']);
    }
}
