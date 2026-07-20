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
        \App\Models\Sala::create(['clinica_id' => 1, 'nombre' => 'Sala 1', 'activa' => true]);
        \App\Models\Sala::create(['clinica_id' => 1, 'nombre' => 'Sala 2', 'activa' => true]);
    }
}
