<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->text('alergias')->nullable()->after('diagnostico_preliminar');
            $table->text('enfermedades')->nullable()->after('alergias');
            $table->text('medicamentos_actuales')->nullable()->after('enfermedades');
            $table->text('antecedentes_medicos')->nullable()->after('medicamentos_actuales');
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn([
                'alergias',
                'enfermedades',
                'medicamentos_actuales',
                'antecedentes_medicos',
            ]);
        });
    }
};
