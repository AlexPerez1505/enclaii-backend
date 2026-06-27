<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predicciones_pre_estudio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')
                ->constrained('citas')
                ->onDelete('cascade');
            $table->json('posibles_hallazgos');
            $table->text('recomendacion_clinica');
            $table->string('plantilla_sugerida');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predicciones_pre_estudio');
    }
};
