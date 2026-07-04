<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('estudio_id')->constrained('estudios')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('users');
            $table->foreignId('plantilla_id')
                ->nullable()
                ->constrained('plantillas')
                ->nullOnDelete();

            $table->text('contenido_texto')->nullable();
            $table->longText('contenido_html')->nullable();
            $table->boolean('contiene_hallazgos_criticos')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
