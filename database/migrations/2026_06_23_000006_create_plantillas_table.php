<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Catálogo de plantillas de reporte (informe e imágenes)
        Schema::create('plantillas', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();        // ej: 'colonoscopia', 'img2'
            $table->string('nombre');                 // nombre visible
            $table->string('descripcion')->nullable();
            $table->string('tipo_plantilla')->default('informe'); // 'informe' | 'imagenes'
            $table->string('tipo_estudio')->nullable();           // ej: 'Colonoscopia'
            $table->string('titulo')->nullable();                 // ej: 'INFORME DE COLONOSCOPIA'
            $table->string('subtitulo')->nullable();              // ej: 'COLONOSCOPIA'
            $table->json('secciones')->nullable();                // secciones del informe
            $table->unsignedInteger('columnas')->nullable();      // plantillas de imágenes
            $table->unsignedInteger('num_imagenes')->nullable();  // total de imágenes a cargar
            $table->json('configuracion')->nullable();            // posiciones logo/nombre/anat/firma
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
