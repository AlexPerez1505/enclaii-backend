<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Estudio;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $estudiosSinReporte = Estudio::whereDoesntHave('reportes')->count();

        // Auto-cancelar citas próximas cuya fecha/hora ya pasó
        Cita::query()
            ->where('estado', 'proximo')
            ->whereRaw("CONCAT(fecha, ' ', hora) <= ?", [now()->format('Y-m-d H:i:s')])
            ->update(['estado' => 'cancelado']);

        // Próximo paciente: la cita pendiente más cercana (solo futuras)
        $proximaCita = Cita::with('paciente')
            ->whereNotIn('estado', ['cancelado', 'completado'])
            ->whereRaw("CONCAT(fecha, ' ', hora) >= ?", [now()->format('Y-m-d H:i:s')])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->first();

        // Pacientes pendientes HOY: citas de hoy futuras y no completadas/canceladas
        $pendientesHoy = Cita::with('paciente')
            ->whereDate('fecha', now()->toDateString())
            ->whereNotIn('estado', ['completado', 'cancelado'])
            ->whereTime('hora', '>=', now()->format('H:i:s'))
            ->orderBy('hora')
            ->get();

        // Citas por estado para el donut del resumen
        $citasProximas = Cita::where('estado', 'proximo')->count();
        $citasCompletadas = Cita::where('estado', 'completado')->count();
        $citasCanceladas = Cita::where('estado', 'cancelado')->count();

        // Resumen del mes (coincide con el mes mostrado en el widget de agenda)
        $widgetMes = (int) $request->query('widget_mes', now()->month);
        $widgetAnio = (int) $request->query('widget_anio', now()->year);
        $inicioMes = \Carbon\Carbon::create($widgetAnio, $widgetMes, 1)->startOfDay();
        $finMes = $inicioMes->copy()->endOfMonth();

        $citasProximasMes = Cita::where('estado', 'proximo')
            ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->count();
        $citasCompletadasMes = Cita::where('estado', 'completado')
            ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->count();
        $citasCanceladasMes = Cita::where('estado', 'cancelado')
            ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->count();

        $pendientesMes = Cita::with('paciente')
            ->whereBetween('fecha', [$inicioMes->toDateString(), $finMes->toDateString()])
            ->whereNotIn('estado', ['completado', 'cancelado'])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        if ($request->expectsJson()) {
            $mapCita = function (Cita $cita) {
                return [
                    'id' => $cita->id,
                    'paciente' => $cita->paciente?->nombre_completo ?? $cita->paciente_nombre,
                    'fecha' => optional($cita->fecha)->format('Y-m-d'),
                    'hora' => $cita->hora,
                    'procedimiento' => $cita->procedimiento,
                    'estado' => $cita->estado,
                    'estado_texto' => $cita->estado_texto,
                    'medico' => $cita->paciente?->medico,
                ];
            };

            return response()->json([
                'ok' => true,
                'dashboard' => [
                    'next_patient' => $proximaCita ? $mapCita($proximaCita) : null,
                    'reportes_pendientes' => $estudiosSinReporte,
                    'summary' => [
                        'total_citas' => $citasProximas + $citasCompletadas + $citasCanceladas,
                        'citas_proximas' => $citasProximasMes,
                        'citas_completadas' => $citasCompletadasMes,
                        'citas_canceladas' => $citasCanceladasMes,
                    ],
                    'pendientes_hoy' => $pendientesHoy->map($mapCita)->values(),
                    'proximos_estudios' => $pendientesMes->take(5)->map($mapCita)->values(),
                ],
            ]);
        }

        return view('dashboard.index', compact(
            'estudiosSinReporte', 'proximaCita', 'pendientesHoy',
            'citasProximas', 'citasCompletadas', 'citasCanceladas',
            'citasProximasMes', 'citasCompletadasMes', 'citasCanceladasMes',
            'pendientesMes', 'widgetMes', 'widgetAnio'
        ));
    }
}
