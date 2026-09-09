<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Registro de seguimiento para la generación de reportes con IA en segundo
     * plano (cola). El frontend hace polling sobre esta tabla mientras el Job
     * llama a OpenAI, en vez de mantener bloqueado un worker de PHP-FPM.
     */
    public function up(): void
    {
        Schema::create('solicitudes_reporte_ia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinica_id')->nullable()->constrained('clinicas')->cascadeOnDelete();
            $table->foreignId('estudio_id')->constrained('estudios')->cascadeOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('estado', 20)->default('pendiente'); // pendiente | procesando | listo | error
            $table->json('resultado')->nullable();
            $table->foreignId('reporte_id')->nullable()->constrained('reportes')->nullOnDelete();
            $table->text('error_mensaje')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_reporte_ia');
    }
};
