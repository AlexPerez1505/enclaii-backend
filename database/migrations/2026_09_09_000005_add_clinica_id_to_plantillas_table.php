<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A diferencia de procedimientos/hallazgos, las plantillas globales
     * (clinica_id NULL) se mantienen como plantillas por defecto del sistema,
     * visibles para todas las clínicas. Cuando una clínica personaliza una
     * plantilla (logo, imagen anatómica, secciones, etc.) se crea una copia
     * propia con su clinica_id, que "sombrea" a la plantilla global sin
     * afectar a las demás clínicas. Ver Plantilla::visibleForCurrentClinica()
     * y PlantillaController::update().
     */
    public function up(): void
    {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->foreignId('clinica_id')
                ->nullable()
                ->after('id')
                ->constrained('clinicas')
                ->cascadeOnDelete();
        });

        Schema::table('plantillas', function (Blueprint $table) {
            $table->dropUnique('plantillas_clave_unique');
        });

        Schema::table('plantillas', function (Blueprint $table) {
            $table->unique(['clave', 'clinica_id']);
        });
    }

    public function down(): void
    {
        Schema::table('plantillas', function (Blueprint $table) {
            $table->dropUnique(['clave', 'clinica_id']);
        });

        Schema::table('plantillas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('clinica_id');
        });

        Schema::table('plantillas', function (Blueprint $table) {
            $table->unique('clave');
        });
    }
};
