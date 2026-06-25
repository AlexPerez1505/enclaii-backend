<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudio_archivos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('estudio_id')
                ->constrained('estudios')
                ->cascadeOnDelete();

            $table->foreignId('paciente_id')
                ->nullable()
                ->constrained('pacientes')
                ->nullOnDelete();

            $table->enum('tipo', ['imagen', 'video', 'documento', 'otro'])->default('imagen');
            $table->string('categoria')->nullable();

            $table->string('nombre_original');
            $table->string('nombre');
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->default(0);

            $table->text('descripcion')->nullable();
            $table->timestamp('capturado_en')->nullable();

            $table->timestamps();

            $table->index(['estudio_id', 'tipo']);
            $table->index('paciente_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudio_archivos');
    }
};
