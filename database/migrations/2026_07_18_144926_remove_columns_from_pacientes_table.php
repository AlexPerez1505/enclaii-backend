<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            // Eliminar las columnas
            $table->dropColumn(['anestesiologo', 'referido_por', 'equipo_utilizado']);
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            // En caso de que necesites revertir (hacer rollback), volvemos a añadirlas
            $table->string('anestesiologo')->nullable();
            $table->string('referido_por')->nullable();
            $table->string('equipo_utilizado')->nullable();
        });
    }
};