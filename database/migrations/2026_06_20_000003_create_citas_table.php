<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paciente_id')
                ->nullable()
                ->constrained('pacientes')
                ->cascadeOnDelete();

            $table->string('paciente_nombre');
            $table->string('procedimiento')->nullable();

            $table->date('fecha');
            $table->time('hora');

            $table->unsignedSmallInteger('duracion_minutos')->default(60);

            $table->enum('estado', [
                'completado',
                'en_espera',
                'cancelado',
                'proximo',
            ])->default('proximo');

            $table->string('sala')->nullable();
            $table->text('notas')->nullable();

            $table->timestamps();

            $table->index(['fecha', 'hora']);
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
