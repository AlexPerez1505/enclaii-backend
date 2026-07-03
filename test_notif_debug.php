<?php
$userId = App\Models\User::first()?->id ?? 1;
$cita = App\Models\Cita::first();

echo "user_id: " . $userId . "\n";
echo "cita_id: " . ($cita ? $cita->id : 'sin citas') . "\n";
echo "notificaciones antes: " . App\Models\Notification::count() . "\n";

if ($cita) {
    broadcast(new App\Events\CitaEstadoChanged($cita, 'proximo', 'en_espera', 'pendiente', $userId));
    echo "notificaciones despues: " . App\Models\Notification::count() . "\n";
    $last = App\Models\Notification::latest()->first();
    if ($last) {
        echo "ultima notif: user_id={$last->user_id}, tipo={$last->tipo}, read=" . ($last->read ? 'true' : 'false') . "\n";
    }
} else {
    echo "sin cita para broadcast\n";
}
