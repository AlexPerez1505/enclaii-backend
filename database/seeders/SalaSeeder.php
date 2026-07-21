<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinicas = \App\Models\Clinica::pluck('id');

        if ($clinicas->isEmpty()) {
            $clinicas = [1];
        }

        foreach ($clinicas as $clinicaId) {
            \App\Models\Sala::firstOrCreate(
                ['clinica_id' => $clinicaId, 'nombre' => 'Sala 1'],
                ['activa' => true]
            );
            \App\Models\Sala::firstOrCreate(
                ['clinica_id' => $clinicaId, 'nombre' => 'Sala 2'],
                ['activa' => true]
            );
        }
    }
}
