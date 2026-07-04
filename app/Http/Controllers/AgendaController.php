<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
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

        return view('agenda.index', compact('citasAgenda', 'citasHoy'));
    }

    public function create(Request $request)
    {
        $pacientes = Paciente::query()
            ->orderBy('nombre_completo')
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

        return view('agenda.agendar.index', compact('pacientes', 'citasAgenda', 'citasHoy', 'citaEditar', 'pacienteSeleccionado'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => ['nullable', 'exists:pacientes,id'],
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
            'paciente_id' => ['nullable', 'exists:pacientes,id'],
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

        $cita->update($validated);

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

        $cita->update($validated);

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
        $cita->delete();

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
            'sala' => $cita->sala ?? 'Sala 3',
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
            'sala' => $cita->sala ?? 'Sala 3',
            'notas' => $cita->notas,
            'delete_url' => route('agenda.citas.destroy', $cita),
            'update_url' => route('agenda.citas.update', $cita),
            'estado_url' => route('agenda.citas.estado', $cita),
            'reprogramar_url' => route('agendar', ['cita_id' => $cita->id]),
        ];
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
