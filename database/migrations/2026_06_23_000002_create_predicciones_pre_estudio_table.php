<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 2. Predicciones Pre-Estudio (IA que analiza antes del procedimiento)
        Schema::create('predicciones_pre_estudio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas')->onDelete('cascade');
            $table->json('posibles_hallazgos'); // Predicción basada en notas de la cita
            $table->text('recomendacion_clinica'); // Guía para el médico
            $table->string('plantilla_sugerida');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predicciones_pre_estudio');
    }
};
