<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProcedimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * DatabaseSeeder corre con WithoutModelEvents, así que el hook que
     * asigna clinica_id automáticamente (BelongsToClinica) no se ejecuta
     * aquí. Por eso se asigna explícitamente para cada clínica existente,
     * igual que SalaSeeder, y así ningún procedimiento queda huérfano
     * (clinica_id null) e invisible para todas las clínicas.
     */
    public function run(): void
    {
        $clinicas = \App\Models\Clinica::pluck('id');

        if ($clinicas->isEmpty()) {
            return;
        }

        $items = ['Colonoscopia', 'Duodenoscopia', 'Endoscopia', 'Laparoscopia'];

        foreach ($clinicas as $clinicaId) {
            foreach ($items as $item) {
                \App\Models\Procedimiento::firstOrCreate([
                    'clinica_id' => $clinicaId,
                    'nombre' => $item,
                ]);
            }
        }
    }
}
