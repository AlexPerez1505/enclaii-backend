<?php

namespace App\Console\Commands;

use App\Models\Cita;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerarNotificacionesCitas extends Command
{
    protected $signature = 'notificaciones:citas';
    protected $description = 'Genera notificaciones de recordatorio para citas próximas (llamar via cron-job.org)';

    public function handle(): int
    {
        $ahora    = Carbon::now();
        $en1hora  = $ahora->copy()->addHour();
        $en24h    = $ahora->copy()->addHours(24);

        $citas = Cita::query()
            ->with('paciente')
            ->where('estado', 'proximo')
            ->whereBetween('fecha', [$ahora->toDateString(), $en24h->toDateString()])
            ->get();

        $creadas = 0;

        foreach ($citas as $cita) {
            $fechaHoraCita = Carbon::parse($cita->fecha->format('Y-m-d') . ' ' . $cita->hora);

            $minutosRestantes = $ahora->diffInMinutes($fechaHoraCita, false);

            if ($minutosRestantes < 0) {
                continue;
            }

            $es1h  = $minutosRestantes <= 60 && $minutosRestantes > 0;
            $es24h = $minutosRestantes <= 1440 && $minutosRestantes > 60;

            if (!$es1h && !$es24h) {
                continue;
            }

            $tipo  = $es1h ? 'recordatorio_1h' : 'recordatorio_24h';
            $titulo = $es1h ? 'Cita en 1 hora' : 'Cita mañana';

            $usuarios = User::where('clinica_id', $cita->clinica_id)->pluck('id');

            foreach ($usuarios as $userId) {
                $yaExiste = Notification::where('user_id', $userId)
                    ->where('tipo', $tipo)
                    ->whereJsonContains('data->cita_id', $cita->id)
                    ->exists();

                if ($yaExiste) {
                    continue;
                }

                Notification::create([
                    'user_id' => $userId,
                    'tipo'    => $tipo,
                    'data'    => [
                        'cita_id'  => $cita->id,
                        'paciente' => $cita->paciente?->nombre_completo ?? $cita->paciente_nombre,
                        'fecha'    => $cita->fecha->format('d/m/Y'),
                        'hora'     => substr($cita->hora, 0, 5),
                        'titulo'   => $titulo,
                    ],
                    'read' => false,
                ]);

                $creadas++;
            }
        }

        $this->info("Notificaciones generadas: {$creadas}");

        return 0;
    }
}
