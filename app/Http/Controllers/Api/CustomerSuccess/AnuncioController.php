<?php

namespace App\Http\Controllers\Api\CustomerSuccess;

use App\Events\AnuncioPublicado as AnuncioPublicadoEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerSuccess\StoreAnuncioRequest;
use App\Mail\AnuncioPublicado;
use App\Models\Anuncio;
use App\Models\Notification;
use App\Models\User;
use App\Services\HtmlPurifierService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $validated['canales'] = $validated['canales'] ?? ['web'];
        $validated['activo'] = $this->shouldPublishImmediately($validated['fecha_publicacion'] ?? null);

        $anuncio = Anuncio::create($validated);

        if ($anuncio->activo) {
            $this->publishNow($anuncio, $request->user());
        }

        return response()->json($anuncio, 201);
    }

    /**
     * Determina si el anuncio debe publicarse inmediatamente.
     */
    private function shouldPublishImmediately(?string $fechaPublicacion): bool
    {
        if (empty($fechaPublicacion)) {
            return true;
        }

        return now()->gte($fechaPublicacion);
    }

    /**
     * Publica un anuncio inmediatamente: notificaciones + broadcast.
     */
    public function publishNow(Anuncio $anuncio, ?User $creator = null): void
    {
        $creator ??= $anuncio->user;
        $targetUsers = $this->targetUsersFor($anuncio, $creator);

        if (in_array('web', $anuncio->canales ?? [], true)) {
            $this->dispatchWebNotifications($anuncio, $targetUsers);
        }

        if (in_array('email', $anuncio->canales ?? [], true)) {
            $this->dispatchEmailNotifications($anuncio, $targetUsers);
        }

        broadcast(new AnuncioPublicadoEvent($anuncio, $targetUsers->pluck('id')->all()));
    }

    /**
     * Filtra usuarios según el público objetivo del anuncio.
     */
    private function targetUsersFor(Anuncio $anuncio, User $creator): \Illuminate\Support\Collection
    {
        $query = User::whereKeyNot($creator->id);

        return match ($anuncio->publico_objetivo) {
            'doctores' => $query->get()->filter(fn (User $u) => ! $u->hasRole('Customer Success')),
            'administradores' => $query->role('Customer Success')->get(),
            default => $query->get(),
        };
    }

    /**
     * Crea notificaciones en la base de datos para el canal web.
     */
    private function dispatchWebNotifications(Anuncio $anuncio, \Illuminate\Support\Collection $users): void
    {
        $now = now();
        $settingKey = self::CATEGORY_SETTING_MAP[$anuncio->tipo] ?? null;

        $notifications = $users
            ->filter(function (User $user) use ($settingKey) {
                if ($settingKey === null) {
                    return true;
                }

                return $user->resolvedSettings()[$settingKey] ?? true;
            })
            ->map(fn (User $user) => [
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
    }

    /**
     * Envía el anuncio por correo electrónico.
     */
    private function dispatchEmailNotifications(Anuncio $anuncio, \Illuminate\Support\Collection $users): void
    {
        $users->each(fn (User $user) => Mail::to($user->email)->queue(new AnuncioPublicado($anuncio)));
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
