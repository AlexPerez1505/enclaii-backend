<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 4. Reportes (Documento clínico)
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudio_id')->constrained();
            $table->foreignId('usuario_id')->constrained('users');
            $table->text('contenido_texto');
            $table->boolean('contiene_hallazgos_criticos')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
