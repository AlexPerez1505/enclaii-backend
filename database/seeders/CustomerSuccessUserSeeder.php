<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSuccessUserSeeder extends Seeder
{
    /**
     * Crea (o encuentra) un usuario CS y le asigna el rol.
     * Ejecutar: php artisan db:seed --class=CustomerSuccessUserSeeder
     */
    public function run(): void
    {
        // Primero asegura que el rol exista
        $this->call(CustomerSuccessRolesSeeder::class);

        // Crea o encuentra el usuario CS
        $user = User::firstOrCreate(
            ['email' => 'cs@enclaii.com'],
            [
                'name' => 'Customer Success',
                'password' => Hash::make('Enclaii2026!'),
            ]
        );

        // Asigna el rol
        if (! $user->hasRole('Customer Success')) {
            $user->assignRole('Customer Success');
        }

        // Los usuarios CS no deben pertenecer a ninguna clínica.
        if ($user->clinica_id || $user->clinica_rol) {
            $user->forceFill(['clinica_id' => null, 'clinica_rol' => null])->saveQuietly();
        }

        $this->command->info("Usuario CS listo: {$user->email}");
    }
}
