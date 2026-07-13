<?php

namespace App\Http\Controllers;

use App\Events\EstudioCompletado;
use App\Models\Cita;
use App\Models\Estudio;
use App\Models\EstudioArchivo;
use App\Models\Paciente;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NuevoEstudioController extends Controller
{
    public function __construct(
        private readonly ActivityLogger $activity,
    ) {}

    public function index()
    {
        /*
         * Esta pantalla debe mostrar primero la lista/dashboard,
         * NO mandar directo a crear.
         */
        $pacientes = Paciente::query()
            ->withCount('estudios')
            ->orderBy('nombre_completo')
            ->get();

        $estudios = Estudio::query()
            ->with(['paciente', 'archivos'])
            ->latest()
            ->get();

        return view('estudios.dashboard.index', compact('pacientes', 'estudios'));
    }

    public function crear(Request $request)
    {
        /*
         * Pacientes reales para el dropdown/buscador.
         */
        $pacientes = Paciente::query()
            ->orderBy('nombre_completo')
            ->get();

        $estudio = null;

        if ($request->filled('estudio_id')) {
            $estudio = Estudio::query()
                ->with(['paciente', 'archivos'])
                ->find($request->query('estudio_id'));
        }

        return view('estudios.crear', compact('pacientes', 'estudio'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => [
                'required',
                Rule::exists('pacientes', 'id')->where('clinica_id', $request->user()->clinica_id),
            ],
            'cita_id' => [
                'nullable',
                Rule::exists('citas', 'id')->where('clinica_id', $request->user()->clinica_id),
            ],
            'tipo' => ['nullable', 'string', 'max:255'],
            'fecha' => ['nullable', 'date'],
            'medico' => ['nullable', 'string', 'max:255'],
            'sala' => ['nullable', 'string', 'max:255'],
            'equipo' => ['nullable', 'string', 'max:255'],
            'diagnostico' => ['nullable', 'string'],
            'descripcion' => ['nullable', 'string'],
            'observaciones' => ['nullable', 'string'],
            'estado' => ['nullable', Rule::in(['en_proceso', 'completado', 'cancelado', 'archivado'])],
        ]);

        $paciente = Paciente::findOrFail($validated['paciente_id']);

        $validated['paciente_nombre'] = $paciente->nombre_completo;
        $validated['folio'] = $this->generarFolioEstudio();
        $validated['fecha'] = $validated['fecha'] ?? today();
        $validated['estado'] = $validated['estado'] ?? 'en_proceso';
        $validated['hora_inicio'] = now()->format('H:i:s');

        // Vincular cita: explícita o la primera cita del paciente para la fecha del estudio.
        if (empty($validated['cita_id'])) {
            $cita = Cita::where('paciente_id', $paciente->id)
                ->whereDate('fecha', $validated['fecha'])
                ->whereNotIn('estado', ['completado', 'cancelado'])
                ->orderBy('hora')
                ->first();

            if ($cita) {
                $validated['cita_id'] = $cita->id;
            }
        }

        $estudio = Estudio::create($validated);
        $this->activity->record(
            'study_created',
            'studies',
            'Creó el estudio '.$estudio->folio,
            $estudio,
            request: $request,
        );

        // La cita pasa a "en espera" mientras se realiza el estudio.
        if ($estudio->cita_id && $estudio->cita?->estado !== 'completado') {
            $estudio->cita->update(['estado' => 'en_espera']);
        }

        session([
            'estudio_activo_id'              => $estudio->id,
            'ultimo_estudio_completado_id'    => null,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => 'Estudio registrado correctamente.',
                'estudio' => $this->estudioPayload($estudio->fresh(['paciente', 'archivos'])),
                'redirect' => route('nuevo-estudio.grabando', ['estudio_id' => $estudio->id]),
            ]);
        }

        return redirect()
            ->route('nuevo-estudio.grabando', ['estudio_id' => $estudio->id])
            ->with('success', 'Estudio registrado correctamente.');
    }

    public function capturas(Request $request)
    {
        $estudio = $this->resolverEstudio($request);

        $archivos = $estudio
            ? $estudio->archivos()->latest()->get()
            : collect();

        return view('estudios.caputras.index', compact('estudio', 'archivos'));
    }

    public function guardarCapturas(Request $request)
    {
        $validated = $request->validate([
            'estudio_id' => [
                'nullable',
                Rule::exists('estudios', 'id')->where('clinica_id', $request->user()->clinica_id),
            ],
            'files' => ['required', 'array'],
            'files.*' => ['file', 'mimes:jpg,jpeg,png,webp,mp4,mov,avi,mkv,webm', 'max:51200'],
            'categoria' => ['nullable', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
        ]);

        $estudio = $this->resolverEstudio($request, true);

        $guardados = [];

        foreach ($request->file('files', []) as $file) {
            $guardados[] = $this->guardarArchivoEstudio(
                estudio: $estudio,
                file: $file,
                categoria: $validated['categoria'] ?? 'captura',
                descripcion: $validated['descripcion'] ?? null
            );
        }

        return response()->json([
            'ok' => true,
            'message' => 'Capturas guardadas correctamente.',
            'archivos' => collect($guardados)->map(fn ($archivo) => $this->archivoPayload($archivo))->values(),
        ]);
    }

    public function importar(Request $request)
    {
        $estudio = $this->resolverEstudio($request);

        return view('estudios.importar', compact('estudio'));
    }

    public function importarStore(Request $request)
    {
        $validated = $request->validate([
            'estudio_id' => [
                'nullable',
                Rule::exists('estudios', 'id')->where('clinica_id', $request->user()->clinica_id),
            ],
            'files' => ['required', 'array'],
            'files.*' => ['file', 'mimes:jpg,jpeg,png,webp,mp4,mov,avi,mkv,webm,pdf', 'max:51200'],
            'categoria' => ['nullable', 'string', 'max:255'],
        ]);

        $estudio = $this->resolverEstudio($request, true);

        $guardados = [];

        foreach ($request->file('files', []) as $file) {
            $guardados[] = $this->guardarArchivoEstudio(
                estudio: $estudio,
                file: $file,
                categoria: $validated['categoria'] ?? 'importado',
                descripcion: null
            );
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => 'Archivos importados correctamente.',
                'archivos' => collect($guardados)->map(fn ($archivo) => $this->archivoPayload($archivo))->values(),
                'redirect' => route('nuevo-estudio.capturas', ['estudio_id' => $estudio->id]),
            ]);
        }

        return redirect()
            ->route('nuevo-estudio.capturas', ['estudio_id' => $estudio->id])
            ->with('success', 'Archivos importados correctamente.');
    }

    public function configuracion(Request $request)
    {
        $estudio = $this->resolverEstudio($request);

        return view('estudios.configuracion', compact('estudio'));
    }

    public function guardarConfiguracion(Request $request)
    {
        $validated = $request->validate([
            'estudio_id' => [
                'nullable',
                Rule::exists('estudios', 'id')->where('clinica_id', $request->user()->clinica_id),
            ],
            'video' => ['nullable', 'array'],
            'audio' => ['nullable', 'array'],
            'texto' => ['nullable', 'array'],
        ]);

        $estudio = $this->resolverEstudio($request, true);

        $estudio->update([
            'configuracion_video' => $validated['video'] ?? [],
            'configuracion_audio' => $validated['audio'] ?? [],
            'configuracion_texto' => $validated['texto'] ?? [],
        ]);
        $this->activity->record(
            'study_configuration_updated',
            'studies',
            'Actualizó la configuración del estudio '.$estudio->folio,
            $estudio,
            request: $request,
        );

        return response()->json([
            'ok' => true,
            'message' => 'Configuración guardada correctamente.',
            'estudio' => $this->estudioPayload($estudio->fresh()),
        ]);
    }

    public function grabando(Request $request)
    {
        /* Resolver sin crear nunca en esta ruta */
        $estudio = $this->resolverEstudio($request, false);

        if (!$estudio) {
            $ultimoId = session('ultimo_estudio_completado_id');
            if ($ultimoId) $estudio = Estudio::find($ultimoId);
        }

        /* Estudio completado → redirigir a su propia página */
        if ($estudio && $estudio->estado === 'completado') {
            return redirect()->route('nuevo-estudio.finalizado', ['estudio_id' => $estudio->id]);
        }

        /* Sin estudio válido → inicio */
        if (!$estudio) {
            return redirect()->route('nuevo-estudio');
        }

        return view('estudios.grabando.index', compact('estudio'));
    }

    public function finalizado(Request $request)
    {
        $estudio = $this->resolverEstudio($request, false);

        if (!$estudio) {
            $ultimoId = session('ultimo_estudio_completado_id');
            if ($ultimoId) $estudio = Estudio::find($ultimoId);
        }

        /* Si no hay estudio completado, redirigir al inicio */
        if (!$estudio || $estudio->estado !== 'completado') {
            return redirect()->route('nuevo-estudio');
        }

        $capturas = $estudio->capturas()->latest()->get();

        return view('estudios.finalizado.index', compact('estudio', 'capturas'));
    }

    public function finalizarGrabacion(Request $request)
    {
        $validated = $request->validate([
            'estudio_id' => [
                'nullable',
                Rule::exists('estudios', 'id')->where('clinica_id', $request->user()->clinica_id),
            ],
            'duracion_segundos' => ['nullable', 'integer', 'min:0'],
            'video' => ['nullable', 'file', 'mimes:mp4,mov,avi,mkv,webm', 'max:512000'],
        ]);

        $estudio = $this->resolverEstudio($request, true);

        $videoPath = $estudio->video_path;

        if ($request->hasFile('video')) {
<<<<<<< HEAD
            $videoPath = media_store(
                $request->file('video'),
                "clinicas/{$request->user()->clinica_id}/estudios/{$estudio->id}/videos",
=======
            $videoPath = $request->file('video')->store(
                "clinicas/{$request->user()->clinica_id}/estudios/{$estudio->id}/videos",
                'public',
            );

            $this->guardarArchivoEstudio(
                estudio: $estudio,
                file: $request->file('video'),
                categoria: 'grabacion',
                descripcion: 'Grabación del estudio',
>>>>>>> origin/main
            );
        }

        $estudio->update([
            'estado' => 'completado',
            'hora_fin' => now()->format('H:i:s'),
            'duracion_segundos' => $validated['duracion_segundos'] ?? $estudio->duracion_segundos,
            'video_path' => $videoPath,
        ]);
        $this->activity->record(
            'study_completed',
            'studies',
            'Finalizó el estudio '.$estudio->folio,
            $estudio,
            request: $request,
        );

        // Al finalizar el estudio, la cita vinculada se marca como completada.
        if ($estudio->cita_id && $estudio->cita) {
            $estudio->cita->update(['estado' => 'completado']);
        }

        broadcast(new EstudioCompletado($estudio->fresh()));

        session([
            'ultimo_estudio_completado_id' => $estudio->id,
        ]);
        session()->forget('estudio_activo_id');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => 'Estudio finalizado correctamente.',
                'estudio' => $this->estudioPayload($estudio->fresh(['paciente', 'archivos'])),
                'redirect' => route('nuevo-estudio', ['estudio_id' => $estudio->id]),
            ]);
        }

        return redirect()
            ->route('nuevo-estudio', ['estudio_id' => $estudio->id])
            ->with('success', 'Estudio finalizado correctamente.');
    }

    public function destroyArchivo(EstudioArchivo $archivo)
    {
        $estudio = $archivo->estudio;

<<<<<<< HEAD
        media_delete($archivo->path);
=======
        if ($archivo->path && Storage::disk('public')->exists($archivo->path)) {
            Storage::disk('public')->delete($archivo->path);
        }
>>>>>>> origin/main

        $archivo->delete();
        $this->activity->record(
            'study_file_deleted',
            'studies',
            'Eliminó un archivo del estudio '.($estudio?->folio ?? '#'.$archivo->estudio_id),
            $estudio,
        );

        return response()->json([
            'ok' => true,
            'message' => 'Archivo eliminado correctamente.',
        ]);
    }

    public function destroyImagenGaleria(EstudioArchivo $archivo)
    {
        abort_unless($archivo->tipo === 'imagen', 404);

        return $this->destroyArchivo($archivo);
    }

    private function resolverEstudio(Request $request, bool $crearSiNoExiste = false): ?Estudio
    {
        $id = $request->input('estudio_id')
            ?? $request->query('estudio_id')
            ?? session('estudio_activo_id');

        if ($id) {
            $estudio = Estudio::query()
                ->with(['paciente', 'archivos'])
                ->find($id);

            if ($estudio) {
                session(['estudio_activo_id' => $estudio->id]);

                return $estudio;
            }
        }

        if (!$crearSiNoExiste) {
            return null;
        }

        $paciente = Paciente::query()
            ->orderBy('nombre_completo')
            ->first();

        if (!$paciente) {
            abort(422, 'Primero registra al menos un paciente.');
        }

        $estudio = Estudio::create([
            'paciente_id' => $paciente->id,
            'paciente_nombre' => $paciente->nombre_completo,
            'folio' => $this->generarFolioEstudio(),
            'tipo' => 'Colonoscopia',
            'fecha' => today(),
            'estado' => 'en_proceso',
            'hora_inicio' => now()->format('H:i:s'),
        ]);

        session(['estudio_activo_id' => $estudio->id]);

        return $estudio;
    }

    private function guardarArchivoEstudio(Estudio $estudio, $file, ?string $categoria = null, ?string $descripcion = null): EstudioArchivo
    {
<<<<<<< HEAD
        $path = media_store(
            $file,
            "clinicas/{$estudio->clinica_id}/estudios/{$estudio->id}/archivos",
=======
        $path = $file->store(
            "clinicas/{$estudio->clinica_id}/estudios/{$estudio->id}/archivos",
            'public',
>>>>>>> origin/main
        );
        $mime = $file->getMimeType();

        $tipo = match (true) {
            str_starts_with((string) $mime, 'image/') => 'imagen',
            str_starts_with((string) $mime, 'video/') => 'video',
            $mime === 'application/pdf' => 'documento',
            default => 'otro',
        };

        return EstudioArchivo::create([
            'estudio_id' => $estudio->id,
            'paciente_id' => $estudio->paciente_id,
            'tipo' => $tipo,
            'categoria' => $categoria,
            'nombre_original' => $file->getClientOriginalName(),
            'nombre' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'path' => $path,
            'mime_type' => $mime,
            'size_bytes' => $file->getSize(),
            'descripcion' => $descripcion,
            'capturado_en' => now(),
        ]);
    }

    private function generarFolioEstudio(): string
    {
        $ultimoId = (int) Estudio::max('id') + 1;

        do {
            $folio = 'E-' . str_pad($ultimoId, 4, '0', STR_PAD_LEFT);
            $ultimoId++;
        } while (Estudio::where('folio', $folio)->exists());

        return $folio;
    }

    private function estudioPayload(Estudio $estudio): array
    {
        return [
            'id' => $estudio->id,
            'folio' => $estudio->folio,
            'paciente_id' => $estudio->paciente_id,
            'cita_id' => $estudio->cita_id,
            'paciente_nombre' => $estudio->paciente?->nombre_completo ?? $estudio->paciente_nombre,
            'tipo' => $estudio->tipo,
            'fecha' => optional($estudio->fecha)->format('Y-m-d'),
            'estado' => $estudio->estado,
            'estado_texto' => $estudio->estado_texto,
            'archivos_count' => $estudio->archivos()->count(),
            'capturas_count' => $estudio->capturas()->count(),
            'videos_count' => $estudio->videos()->count(),
        ];
    }

    private function archivoPayload(EstudioArchivo $archivo): array
    {
        return [
            'id' => $archivo->id,
            'tipo' => $archivo->tipo,
            'nombre' => $archivo->nombre,
            'nombre_original' => $archivo->nombre_original,
            'url' => media_url($archivo->path),
            'path' => $archivo->path,
            'mime_type' => $archivo->mime_type,
            'size_bytes' => $archivo->size_bytes,
            'capturado_en' => optional($archivo->capturado_en)->format('Y-m-d H:i:s'),
            'delete_url' => route('nuevo-estudio.archivos.destroy', $archivo),
        ];
    }
}
