<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('plantillas')) { return; }
        Schema::create('plantillas', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->string('tipo_plantilla')->default('informe');
            $table->string('tipo_estudio')->nullable();
            $table->string('titulo')->nullable();
            $table->string('subtitulo')->nullable();
            $table->json('secciones')->nullable();
            $table->unsignedInteger('columnas')->nullable();
            $table->unsignedInteger('num_imagenes')->nullable();
            $table->json('configuracion')->nullable();
            $table->boolean('solo_imagenes')->default(false);
            $table->boolean('es_predeterminada')->default(false);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantillas');
    }
};
