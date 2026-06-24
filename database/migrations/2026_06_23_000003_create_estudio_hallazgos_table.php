<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 3. Estudio_Hallazgos (La realidad encontrada vs la predicción)
        Schema::create('estudio_hallazgos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudio_id')->constrained(); // Vinculado al estudio real
            $table->foreignId('hallazgo_id')->constrained();
            $table->string('detectado_por'); // 'IA' o 'Medico'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudio_hallazgos');
    }
};
