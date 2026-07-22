<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AnestesiologoSeeder extends Seeder
{
    public function run(): void
    {
        $clinica = \App\Models\User::first()?->clinica;

        $items = [
            [
                'nombres' => 'Laura',
                'apellido_paterno' => 'Chávez',
                'apellido_materno' => 'Herrera',
                'especialidad' => 'Anestesiología',
                'cedula_profesional' => '7654321',
                'correo' => 'laura.chavez@endoclinic.com',
                'telefono' => '5512345678',
                'activo' => true,
            ],
            [
                'nombres' => 'Miguel',
                'apellido_paterno' => 'Romero',
                'apellido_materno' => 'Sánchez',
                'especialidad' => 'Anestesiología',
                'cedula_profesional' => '7894563',
                'correo' => 'miguel.romero@endoclinic.com',
                'telefono' => '5587654321',
                'activo' => true,
            ],
            [
                'nombres' => 'Ana',
                'apellido_paterno' => 'Pérez',
                'apellido_materno' => 'López',
                'especialidad' => 'Anestesiología',
                'cedula_profesional' => '6543210',
                'correo' => 'ana.perez@endoclinic.com',
                'telefono' => '5567891234',
                'activo' => true,
            ],
            [
                'nombres' => 'José',
                'apellido_paterno' => 'González',
                'apellido_materno' => 'Ramírez',
                'especialidad' => 'Anestesiología',
                'cedula_profesional' => '8123456',
                'correo' => 'jose.gonzalez@endoclinic.com',
                'telefono' => '5578901234',
                'activo' => false,
            ],
            [
                'nombres' => 'Sofía',
                'apellido_paterno' => 'Martínez',
                'apellido_materno' => 'Díaz',
                'especialidad' => 'Anestesiología',
                'cedula_profesional' => '9876543',
                'correo' => 'sofia.martinez@endoclinic.com',
                'telefono' => '5543219876',
                'activo' => true,
            ],
        ];

        foreach ($items as $item) {
            \App\Models\Anestesiologo::firstOrCreate(
                ['correo' => $item['correo']],
                array_merge($item, ['clinica_id' => $clinica?->id])
            );
        }
    }
}
