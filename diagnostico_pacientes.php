<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'al222410852@gmail.com';

$users = App\Models\User::where('email', $email)->get();
echo 'Usuarios con ese correo: ' . $users->count() . PHP_EOL;

foreach ($users as $u) {
    echo '  -> ID: ' . $u->id . ' | clinica_id: ' . $u->clinica_id . ' | clinica_rol: ' . $u->clinica_rol . PHP_EOL;
}

foreach ($users as $u) {
    $count = App\Models\Paciente::withoutGlobalScopes()->where('clinica_id', $u->clinica_id)->count();
    echo '  Pacientes en clinica_id ' . $u->clinica_id . ': ' . $count . PHP_EOL;
}

echo 'Total pacientes en toda la BD (sin scope): ' . App\Models\Paciente::withoutGlobalScopes()->count() . PHP_EOL;

echo 'Tokens Sanctum de este usuario:' . PHP_EOL;
foreach ($users as $u) {
    foreach ($u->tokens as $t) {
        echo '  -> token id ' . $t->id . ' name: ' . $t->name . ' created: ' . $t->created_at . PHP_EOL;
    }
}
