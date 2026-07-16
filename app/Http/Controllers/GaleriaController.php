<?php

namespace App\Http\Controllers;

use App\Models\Estudio;
use App\Models\EstudioArchivo;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class GaleriaController extends Controller
{
    public function index(Request $request)
    {
        $colores = [
            'linear-gradient(135deg,#c084fc,#a78bfa)',
            'linear-gradient(135deg,#7dd3fc,#60a5fa)',
            'linear-gradient(135deg,#f9a8d4,#f472b6)',
            'linear-gradient(135deg,#99f6e4,#6ee7b7)',
        ];

        $pacientesDb = Paciente::orderBy('nombre_completo')->get()->values();
        $pacienteIds = $pacientesDb->pluck('id');

        if (! $request->expectsJson()) {
            $medicos = Estudio::query()
                ->whereNotNull('medico')
                ->where('medico', '<>', '')
                ->distinct()
                ->orderBy('medico')
                ->pluck('medico');

            $procedimientos = Estudio::query()
                ->whereNotNull('tipo')
                ->where('tipo', '<>', '')
                ->distinct()
                ->orderBy('tipo')
                ->pluck('tipo');

            $hallazgos = \App\Models\Hallazgo::orderBy('nombre')->get(['id', 'nombre']);

            $estudiosPorPaciente = Estudio::with([
                    'archivos:id,estudio_id,tipo',
                    'estudioHallazgos:id,estudio_id,hallazgo_id,detectado_por',
                ])
                ->whereIn('paciente_id', $pacienteIds)
                ->get()
                ->groupBy('paciente_id');
            $archivosPorPaciente = EstudioArchivo::whereIn('paciente_id', $pacienteIds)
                ->get()
                ->groupBy('paciente_id');

            $pacientes = $pacientesDb->map(function ($p, $i) use ($colores, $estudiosPorPaciente, $archivosPorPaciente) {
                $archivosPaciente = $archivosPorPaciente->get($p->id, collect());
                $estudiosDetalle = $estudiosPorPaciente->get($p->id, collect());
                $fotos = $archivosPaciente->where('tipo', 'imagen')->count();
                $videos = $archivosPaciente->where('tipo', 'video')->count();
                $estudios = $estudiosDetalle->count();
                $ultimoTs = $archivosPaciente->max('capturado_en');
                $ultimo = $ultimoTs ? Carbon::parse($ultimoTs)->format('d/m/Y') : '—';
                $ini = collect(explode(' ', $p->nombre_completo ?? ''))
                    ->filter()->take(2)
                    ->map(fn ($x) => mb_strtoupper(mb_substr($x, 0, 1)))
                    ->implode('') ?: 'PX';

                return [
                    'id' => $p->id,
                    'nombre' => $p->nombre_completo ?? 'Paciente',
                    'telefono' => $p->telefono ?? '',
                    'codigo' => $p->folio ?? $p->identificacion ?? '—',
                    'sexo' => $p->sexo ?? '—',
                    'edad' => $p->edad ? $p->edad.' años' : '—',
                    'ultimo' => $ultimo,
                    'estudios' => $estudios,
                    'fotos' => $fotos,
                    'videos' => $videos,
                    'estado' => 'Activo',
                    'ini' => $ini,
                    'color' => $colores[$i % count($colores)],
                    'filtros' => $estudiosDetalle->map(function ($estudio) {
                        $hallazgosEstudio = $estudio->estudioHallazgos;

                        return [
                            'medico' => $estudio->medico ?? '',
                            'procedimiento' => $estudio->tipo ?? '',
                            'fecha' => $estudio->fecha?->format('Y-m-d') ?? '',
                            'estado' => $estudio->estado ?? '',
                            'archivos' => $estudio->archivos->pluck('tipo')->unique()->values(),
                            'hallazgos' => $hallazgosEstudio->pluck('hallazgo_id')
                                ->map(fn ($id) => (string) $id)
                                ->values(),
                            'hallazgos_ia' => $hallazgosEstudio->contains(
                                fn ($hallazgo) => mb_strtolower($hallazgo->detectado_por ?? '') === 'ia'
                            ),
                        ];
                    })->values(),
                ];
            });

            return view('galeria.index', compact('pacientes', 'medicos', 'procedimientos', 'hallazgos'));
        }

        $estudiosPorPaciente = Estudio::whereIn('paciente_id', $pacienteIds)
            ->orderByDesc('fecha')
            ->get()
            ->groupBy('paciente_id');

        $archivosPorPaciente = EstudioArchivo::with('estudio')
            ->whereIn('paciente_id', $pacienteIds)
            ->orderByDesc('capturado_en')
            ->get()
            ->groupBy('paciente_id');

        $data = $pacientesDb->map(function ($p) use ($estudiosPorPaciente, $archivosPorPaciente) {
            $archivosPaciente = $archivosPorPaciente->get($p->id, collect());
            $estudiosDetalle = $estudiosPorPaciente->get($p->id, collect());
            $fotos = $archivosPaciente->where('tipo', 'imagen')->count();
            $videos = $archivosPaciente->where('tipo', 'video')->count();
            $ultimoEstudio = $estudiosDetalle->first();
            $ultimoTs = $archivosPaciente->max('capturado_en');
            $ini = collect(explode(' ', $p->nombre_completo ?? ''))
                ->filter()->take(2)
                ->map(fn ($x) => mb_strtoupper(mb_substr($x, 0, 1)))
                ->implode('') ?: 'PX';

            $estadoMap = [
                'en_proceso' => 'process',
                'completado' => 'done',
                'archivado' => 'done',
                'cancelado' => 'done',
            ];

            $media = $archivosPaciente->map(function (EstudioArchivo $archivo) {
                $capturadoEn = $archivo->capturado_en ? Carbon::parse($archivo->capturado_en) : null;

                return [
                    'id' => (string) $archivo->id,
                    'type' => $archivo->tipo === 'video' ? 'video' : 'image',
                    'file' => $archivo->nombre_original ?: $archivo->nombre,
                    'study' => $archivo->estudio?->tipo ?? 'Estudio',
                    'date' => $capturadoEn?->format('d/m/Y') ?? '—',
                    'studyDate' => $capturadoEn?->format('Y-m-d') ?? '',
                    'time' => $capturadoEn?->format('H:i') ?? '',
                    'src' => media_url($archivo->path),
                ];
            })->values();

            return [
                'id' => (string) $p->id,
                'patient_id' => $p->id,
                'name' => $p->nombre_completo ?? 'Paciente',
                'initials' => $ini,
                'age' => $p->edad ? $p->edad.' años' : '—',
                'gender' => $p->sexo ?? '—',
                'lastStudy' => $ultimoTs ? Carbon::parse($ultimoTs)->format('d/m/Y') : '—',
                'studyDate' => $ultimoEstudio?->fecha?->format('Y-m-d') ?? '',
                'studies' => $estudiosDetalle->count(),
                'photos' => $fotos,
                'videos' => $videos,
                'status' => 'Activo',
                'studyStatus' => $estadoMap[$ultimoEstudio?->estado] ?? 'pending',
                'doctor' => $ultimoEstudio?->medico ?? $p->medico ?? '',
                'procedure' => $ultimoEstudio?->tipo ?? '',
                'phone' => $p->telefono ?? '',
                'detailStudies' => $estudiosDetalle->count(),
                'media' => $media,
            ];
        })->values();

        return response()->json([
            'ok' => true,
            'patients' => $data,
        ]);
    }
}
