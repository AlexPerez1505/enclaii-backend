<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paciente_id')
                ->nullable()
                ->constrained('pacientes')
                ->nullOnDelete();

            $table->string('paciente_nombre')->nullable();
            $table->string('folio')->unique();

            $table->string('tipo')->nullable();
            $table->date('fecha')->nullable();

            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->unsignedInteger('duracion_segundos')->default(0);

            $table->enum('estado', [
                'en_proceso',
                'completado',
                'cancelado',
                'archivado',
            ])->default('en_proceso');

            $table->string('medico')->nullable();
            $table->string('sala')->nullable();
            $table->string('equipo')->nullable();

            $table->text('diagnostico')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('observaciones')->nullable();

            $table->json('configuracion_video')->nullable();
            $table->json('configuracion_audio')->nullable();
            $table->json('configuracion_texto')->nullable();

            $table->string('video_path')->nullable();
            $table->string('reporte_path')->nullable();

            $table->timestamps();

            $table->index(['paciente_id', 'fecha']);
            $table->index('estado');
            $table->index('tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudios');
    }
};
