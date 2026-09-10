<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProcedimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $items = ['Colonoscopia', 'Duodenoscopia', 'Endoscopia', 'Laparoscopia'];
    foreach ($items as $item) {
        \App\Models\Procedimiento::firstOrCreate(['nombre' => $item]);
    }
    }
}
