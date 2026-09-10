<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Campos para clínicas veterinarias (vertical = 'veterinaria'). Todos
     * nullable para no afectar a los pacientes/clínicas médicas existentes.
     */
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('especie')->nullable()->after('nombre_completo');
            $table->string('raza')->nullable()->after('especie');
            $table->boolean('esterilizado')->nullable()->after('raza');
            $table->string('color_pelaje')->nullable()->after('esterilizado');
            $table->string('microchip')->nullable()->after('color_pelaje');
            $table->string('nombre_tutor')->nullable()->after('identificacion');
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn([
                'especie',
                'raza',
                'esterilizado',
                'color_pelaje',
                'microchip',
                'nombre_tutor',
            ]);
        });
    }
};
