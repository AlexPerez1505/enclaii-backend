<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

$email = 'nuevo-cs@example.com';

$permisos = [
    'customer_success.anuncios.view',
    'customer_success.anuncios.create',
    'customer_success.anuncios.update',
    'customer_success.anuncios.delete',
    'customer_success.politicas.manage',
];

$role = Role::firstOrCreate(
    ['name' => 'Customer Success', 'guard_name' => 'web'],
    ['name' => 'Customer Success', 'guard_name' => 'web']
);

foreach ($permisos as $permiso) {
    Permission::firstOrCreate(
        ['name' => $permiso, 'guard_name' => 'web'],
        ['name' => $permiso, 'guard_name' => 'web']
    );
}

$role->syncPermissions($permisos);

$user = User::where('email', $email)->first();

if (!$user) {
    echo "No se encontró el usuario con correo: {$email}" . PHP_EOL;
    exit(1);
}

if (!$user->hasRole('Customer Success')) {
    $user->assignRole('Customer Success');
}

$roleNames = $user->roles->pluck('name')->implode(', ');
$permissionNames = $user->permissions->pluck('name')->merge($user->getPermissionsViaRoles()->pluck('name'))->unique()->implode(', ');

echo "Usuario: {$user->email}" . PHP_EOL;
echo "Roles: {$roleNames}" . PHP_EOL;
echo "Permisos activos: {$permissionNames}" . PHP_EOL;
