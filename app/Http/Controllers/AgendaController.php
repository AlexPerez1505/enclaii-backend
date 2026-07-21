<?php

namespace App\Http\Controllers;

use App\Events\CitaEstadoChanged;
use App\Models\Bloqueo;
use App\Models\Cita;
use App\Models\Paciente;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        // Auto-cancelar citas 'proximo' cuya fecha/hora ya pasó.
        $now = now();
        Cita::query()
            ->where('estado', 'proximo')
            ->where(function ($query) use ($now) {
                $query->whereDate('fecha', '<', $now->toDateString())
                      ->orWhere(function ($q) use ($now) {
                          $q->whereDate('fecha', $now->toDateString())
                            ->whereTime('hora', '<=', $now->format('H:i:s'));
                      });
            })
            ->update(['estado' => 'cancelado']);

        $citasQuery = Cita::query()
            ->with('paciente')
            ->orderBy('fecha')
            ->orderBy('hora');

        if ($request->expectsJson()) {
            $year = (int) $request->query('year', $now->year);
            $month = (int) $request->query('month', $now->month);
            $inicioMes = Carbon::create($year, $month, 1)->startOfMonth();
            $finMes = $inicioMes->copy()->endOfMonth();

            $citas = $citasQuery
                ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
                ->get();

            return response()->json([
                'ok' => true,
                'citas' => $citas->map(fn (Cita $cita) => $this->citaParaAgenda($cita))->values(),
            ]);
        }

        $citas = $citasQuery->get();

        $citasAgenda = $citas->map(fn (Cita $cita) => $this->citaParaAgenda($cita))->values();

        $citasHoy = Cita::query()
            ->whereDate('fecha', today())
            ->whereIn('estado', ['en_espera', 'proximo'])
            ->count();

        $bloqueos = Bloqueo::query()->orderBy('fecha')->orderBy('hora')->get();

        $bloqueosData = $bloqueos->map(function ($b) {
            $hI = (int) explode(':', $b->hora)[0];
            $mI = (int) (explode(':', $b->hora)[1] ?? 0);
            $duracion = 60;
            if ($b->hora_fin) {
                $hF = (int) explode(':', $b->hora_fin)[0];
                $mF = (int) (explode(':', $b->hora_fin)[1] ?? 0);
                $diff = ($hF * 60 + $mF) - ($hI * 60 + $mI);
                if ($diff > 0) $duracion = $diff;
            }
            return [
                'id'      => $b->id,
                'label'   => $b->label,
                'fecha'   => $b->fecha->format('Y-n-j'),
                'hora'    => $b->hora,
                'hora_fin'=> $b->hora_fin,
                'h'       => $hI,
                'duracion'=> $duracion,
            ];
        })->values();

        return view('agenda.index', compact('citasAgenda', 'citasHoy', 'bloqueosData'));
    }

    public function create(Request $request)
    {
        $pacientes = Paciente::query()
            ->orderBy('nombre_completo')
            ->get();
    
        $salas = \App\Models\Sala::query()
            ->where('clinica_id', $request->user()->clinica_id)
            ->where('activa', true)
            ->get();
        
        $citas = Cita::query()
            ->with('paciente')
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        $citasAgenda = $citas->map(fn (Cita $cita) => $this->citaParaAgenda($cita))->values();

        $citasHoy = Cita::query()
            ->whereDate('fecha', today())
            ->whereIn('estado', ['en_espera', 'proximo'])
            ->count();

        $citaEditar = null;
        $pacienteSeleccionado = null;

        if ($request->filled('cita_id')) {
            $cita = Cita::query()
                ->with('paciente')
                ->find($request->query('cita_id'));

            if ($cita) {
                $citaEditar = $this->citaParaFormulario($cita);
            }
        }

        if ($request->filled('paciente_id')) {
            $pacienteSeleccionado = Paciente::find($request->query('paciente_id'));
        }

        $bloqueos = Bloqueo::query()->orderBy('fecha')->orderBy('hora')->get();
        $bloqueosData = $bloqueos->map(function ($b) {
            $hI = (int) explode(':', $b->hora)[0];  
            $mI = (int) (explode(':', $b->hora)[1] ?? 0);
            $duracion = 60;
            if ($b->hora_fin) {
                $hF = (int) explode(':', $b->hora_fin)[0];
                $mF = (int) (explode(':', $b->hora_fin)[1] ?? 0);
                $diff = ($hF * 60 + $mF) - ($hI * 60 + $mI);
                if ($diff > 0) $duracion = $diff;
            }
            return [
                'id'      => $b->id,
                'label'   => $b->label,
                'fecha'   => $b->fecha->format('Y-n-j'),
                'hora'    => $b->hora,
                'hora_fin'=> $b->hora_fin,
                'h'       => $hI,
                'duracion'=> $duracion,
            ];
        })->values();

        return view('agenda.agendar.index', compact('pacientes', 'salas','citasAgenda', 'citasHoy', 'citaEditar', 'pacienteSeleccionado', 'bloqueosData'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => [
                'nullable',
                Rule::exists('pacientes', 'id')->where('clinica_id', $request->user()->clinica_id),
            ],
            'paciente_nombre' => ['nullable', 'string', 'max:255'],
            'procedimiento' => ['nullable', 'string', 'max:255'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'date_format:H:i'],
            'duracion_minutos' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'estado' => ['nullable', 'in:completado,en_espera,cancelado,proximo'],
            'sala' => ['nullable', 'string', 'max:255'],
            'notas' => ['nullable', 'string'],
        ]);

        $validated = $this->normalizarDatosCita($validated);

        $cita = Cita::create($validated);

        broadcast(new CitaEstadoChanged($cita->fresh(), '', $cita->estado, 'nueva'));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => 'Cita registrada correctamente.',
                'cita' => $this->citaParaAgenda($cita->fresh()),
            ]);
        }

        return redirect()
            ->route('agenda')
            ->with('success', 'Cita registrada correctamente.');
    }

    public function update(Request $request, Cita $cita)
    {
        $validated = $request->validate([
            'paciente_id' => [
                'nullable',
                Rule::exists('pacientes', 'id')->where('clinica_id', $request->user()->clinica_id),
            ],
            'paciente_nombre' => ['nullable', 'string', 'max:255'],
            'procedimiento' => ['nullable', 'string', 'max:255'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'date_format:H:i'],
            'duracion_minutos' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'estado' => ['nullable', 'in:completado,en_espera,cancelado,proximo'],
            'sala' => ['nullable', 'string', 'max:255'],
            'notas' => ['nullable', 'string'],
        ]);

        $validated = $this->normalizarDatosCita($validated, $cita);

        $estadoAnterior = $cita->estado;
        $fechaAnterior = optional($cita->fecha)->format('d/m/Y');
        $horaAnterior = substr($cita->hora, 0, 5);

        $validated['estado'] = 'proximo';

        $cita->update($validated);

        if ($estadoAnterior !== $cita->estado) {
            broadcast(new CitaEstadoChanged(
                $cita->fresh(),
                $estadoAnterior,
                $cita->estado,
                'reprogramada',
                null,
                $fechaAnterior,
                $horaAnterior
            ));
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => 'Cita reprogramada correctamente.',
                'cita' => $this->citaParaAgenda($cita->fresh()),
            ]);
        }

        return redirect()
            ->route('agenda')
            ->with('success', 'Cita reprogramada correctamente.');
    }

    public function cambiarEstado(Request $request, Cita $cita)
    {
        $validated = $request->validate([
            'estado' => ['required', 'in:completado,en_espera,cancelado,proximo'],
        ]);

        $estadoAnterior = $cita->estado;

        $cita->update($validated);

        if ($estadoAnterior !== $cita->estado) {
            $tipo = match($cita->estado) {
                'en_espera'  => 'pendiente',
                'cancelado'  => 'cancelada',
                'completado' => 'completada',
                default      => 'estado',
            };
            broadcast(new CitaEstadoChanged($cita->fresh(), $estadoAnterior, $cita->estado, $tipo));
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => 'Estado actualizado correctamente.',
                'cita' => $this->citaParaAgenda($cita->fresh()),
            ]);
        }

        return redirect()
            ->route('agenda')
            ->with('success', 'Estado actualizado correctamente.');
    }

    public function destroy(Request $request, Cita $cita)
    {
        $snapshot = clone $cita;
        $cita->delete();

        broadcast(new CitaEstadoChanged($snapshot, $snapshot->estado, 'eliminada', 'eliminada'));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => 'Cita eliminada correctamente.',
            ]);
        }

        return redirect()
            ->route('agenda')
            ->with('success', 'Cita eliminada correctamente.');
    }

    private function normalizarDatosCita(array $validated, ?Cita $citaActual = null): array
    {
        if (!empty($validated['paciente_id'])) {
            $paciente = Paciente::find($validated['paciente_id']);

            $validated['paciente_nombre'] = $paciente?->nombre_completo
                ?? $validated['paciente_nombre']
                ?? $citaActual?->paciente_nombre
                ?? 'Paciente sin nombre';
        }

        if (empty($validated['paciente_nombre'])) {
            $validated['paciente_nombre'] = $citaActual?->paciente_nombre ?? 'Paciente sin nombre';
        }

        $validated['duracion_minutos'] = $validated['duracion_minutos']
            ?? $citaActual?->duracion_minutos
            ?? 60;

        $validated['estado'] = $validated['estado']
            ?? $citaActual?->estado
            ?? 'proximo';

        if (!empty($validated['sala'])) {
            if (is_numeric($validated['sala'])) {
                $validated['sala_id'] = (int) $validated['sala'];
            } else {
                $sala = \App\Models\Sala::query()
                    ->where('nombre', $validated['sala'])
                    ->where('clinica_id', auth()->user()->clinica_id)
                    ->first();
                if ($sala) {
                    $validated['sala_id'] = $sala->id;
                }
            }
            unset($validated['sala']);
        }

        return $validated;
    }

    private function citaParaFormulario(Cita $cita): array
    {
        $hora = $this->normalizarHora($cita->hora);

        return [
            'id' => $cita->id,
            'paciente_id' => $cita->paciente_id,
            'paciente_nombre' => $cita->paciente?->nombre_completo ?? $cita->paciente_nombre,
            'procedimiento' => $cita->procedimiento ?? '',
            'fecha' => optional($cita->fecha)->format('Y-m-d'),
            'fecha_formato' => optional($cita->fecha)->format('d/m/Y'),
            'hora' => $hora,
            'hora_formato' => Carbon::createFromFormat('H:i', $hora)->format('g:i A'),
            'duracion_minutos' => $cita->duracion_minutos ?? 60,
            'estado' => $cita->estado,
            'sala_id' => $cita->sala_id, // Usamos el ID del modelo
        'salas' => \App\Models\Sala::where('activa', true)->get(), // Enviamos las salas al array
            'notas' => $cita->notas ?? '',
            'update_url' => route('agenda.citas.update', $cita),
        ];
    }

    private function citaParaAgenda(Cita $cita): array
    {
        $hora = $this->normalizarHora($cita->hora);
        $horaCarbon = Carbon::createFromFormat('H:i', $hora);

        return [
            'id' => $cita->id,
            'fecha' => optional($cita->fecha)->format('Y-m-d'),
            'fecha_key' => optional($cita->fecha)->format('Y-n-j'),
            'hora' => $hora,
            'hora_label' => $horaCarbon->format('H:i'),
            'hora_h' => (int) $horaCarbon->format('G'),
            'paciente_id' => $cita->paciente_id,
            'paciente' => $cita->paciente?->nombre_completo ?? $cita->paciente_nombre,
            'procedimiento' => $cita->procedimiento ?? 'Procedimiento',
            'estado' => $cita->estado,
            'estado_texto' => $cita->estado_texto,
            'cls' => $cita->estado_clase,
            'sala_id' => $cita->sala_id,
            'sala' => $cita->salaRelacion ? $cita->salaRelacion->nombre : ($cita->sala ?? 'Sala 3'),
            'notas' => $cita->notas,
            'delete_url' => route('agenda.citas.destroy', $cita),
            'update_url' => route('agenda.citas.update', $cita),
            'estado_url' => route('agenda.citas.estado', $cita),
            'reprogramar_url' => route('agendar', ['cita_id' => $cita->id]),
        ];
    }

    public function storeBloqueo(Request $request)
    {
        $validated = $request->validate([
            'label'    => ['nullable', 'string', 'max:255'],
            'fechas'   => ['required', 'array', 'min:1', 'max:400'],
            'fechas.*' => ['required', 'date'],
            'hora'     => ['required', 'date_format:H:i'],
            'hora_fin' => ['nullable', 'date_format:H:i'],
        ]);

        $label   = $validated['label'] ?: 'Bloqueo de Tiempo';
        $hora    = $validated['hora'];
        $horaFin = $validated['hora_fin'] ?? null;

        // Calcular duración en minutos
        $duracion = 60;
        if ($horaFin) {
            [$hI, $mI] = array_map('intval', explode(':', $hora));
            [$hF, $mF] = array_map('intval', explode(':', $horaFin));
            $diff = ($hF * 60 + $mF) - ($hI * 60 + $mI);
            if ($diff > 0) $duracion = $diff;
        }

        $fechas = array_unique($validated['fechas']);

        $creados = [];
        foreach ($fechas as $fecha) {
            $bloqueo = Bloqueo::create([
                'label'    => $label,
                'fecha'    => $fecha,
                'hora'     => $hora,
                'hora_fin' => $horaFin,
            ]);
            $creados[] = [
                'id'      => $bloqueo->id,
                'label'   => $bloqueo->label,
                'fecha'   => $bloqueo->fecha->format('Y-n-j'),
                'hora'    => $bloqueo->hora,
                'hora_fin'=> $bloqueo->hora_fin,
                'h'       => (int) explode(':', $hora)[0],
                'duracion'=> $duracion,
            ];
        }

        return response()->json(['ok' => true, 'bloqueos' => $creados]);
    }

    public function destroyBloqueo(Request $request, Bloqueo $bloqueo)
    {
        $bloqueo->delete();

        return response()->json(['ok' => true]);
    }

    private function normalizarHora(string $hora): string
    {
        if (preg_match('/^\d{2}:\d{2}$/', $hora)) {
            return $hora;
        }

        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $hora)) {
            return substr($hora, 0, 5);
        }

        return Carbon::parse($hora)->format('H:i');
    }
}
