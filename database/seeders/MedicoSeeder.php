<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MedicoSeeder extends Seeder
{
    public function run(): void
    {
        $clinica = \App\Models\User::first()?->clinica;

        $items = [
            [
                'nombres' => 'Alberto',
                'apellido_paterno' => 'Vega',
                'apellido_materno' => 'Cruz',
                'especialidad' => 'Gastroenterología',
                'cedula_profesional' => '1234567',
                'correo' => 'alberto.vega@endoclinic.com',
                'telefono' => '5511112222',
                'activo' => true,
            ],
            [
                'nombres' => 'Carmen',
                'apellido_paterno' => 'Solís',
                'apellido_materno' => 'Mora',
                'especialidad' => 'Endoscopia',
                'cedula_profesional' => '2345678',
                'correo' => 'carmen.solis@endoclinic.com',
                'telefono' => '5522223333',
                'activo' => true,
            ],
            [
                'nombres' => 'Emilio',
                'apellido_paterno' => 'Duarte',
                'apellido_materno' => 'Ríos',
                'especialidad' => 'Gastroenterología',
                'cedula_profesional' => '3456789',
                'correo' => 'emilio.duarte@endoclinic.com',
                'telefono' => '5533334444',
                'activo' => true,
            ],
            [
                'nombres' => 'Beatriz',
                'apellido_paterno' => 'León',
                'apellido_materno' => 'Soto',
                'especialidad' => 'Endoscopia',
                'cedula_profesional' => '4567890',
                'correo' => 'beatriz.leon@endoclinic.com',
                'telefono' => '5544445555',
                'activo' => true,
            ],
            [
                'nombres' => 'Rodrigo',
                'apellido_paterno' => 'Núñez',
                'apellido_materno' => 'Vargas',
                'especialidad' => 'Gastroenterología',
                'cedula_profesional' => '5678901',
                'correo' => 'rodrigo.nunez@endoclinic.com',
                'telefono' => '5555556666',
                'activo' => false,
            ],
        ];

        foreach ($items as $item) {
            \App\Models\Medico::firstOrCreate(
                ['correo' => $item['correo']],
                array_merge($item, ['clinica_id' => $clinica?->id])
            );
        }
    }
}
