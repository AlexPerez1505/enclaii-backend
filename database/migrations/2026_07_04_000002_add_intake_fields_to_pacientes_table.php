<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
<<<<<<< HEAD
            if (! Schema::hasColumn('pacientes', 'alergias')) {
                $table->text('alergias')->nullable()->after('diagnostico_preliminar');
            }
            if (! Schema::hasColumn('pacientes', 'enfermedades')) {
                $table->text('enfermedades')->nullable()->after('alergias');
            }
            if (! Schema::hasColumn('pacientes', 'medicamentos_actuales')) {
                $table->text('medicamentos_actuales')->nullable()->after('enfermedades');
            }
            if (! Schema::hasColumn('pacientes', 'antecedentes_medicos')) {
                $table->text('antecedentes_medicos')->nullable()->after('medicamentos_actuales');
            }
=======
            $table->text('alergias')->nullable()->after('diagnostico_preliminar');
            $table->text('enfermedades')->nullable()->after('alergias');
            $table->text('medicamentos_actuales')->nullable()->after('enfermedades');
            $table->text('antecedentes_medicos')->nullable()->after('medicamentos_actuales');
>>>>>>> Ricardo-Galeria
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
<<<<<<< HEAD
            $columns = [];
            if (Schema::hasColumn('pacientes', 'alergias')) {
                $columns[] = 'alergias';
            }
            if (Schema::hasColumn('pacientes', 'enfermedades')) {
                $columns[] = 'enfermedades';
            }
            if (Schema::hasColumn('pacientes', 'medicamentos_actuales')) {
                $columns[] = 'medicamentos_actuales';
            }
            if (Schema::hasColumn('pacientes', 'antecedentes_medicos')) {
                $columns[] = 'antecedentes_medicos';
            }
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
=======
            $table->dropColumn([
                'alergias',
                'enfermedades',
                'medicamentos_actuales',
                'antecedentes_medicos',
            ]);
>>>>>>> Ricardo-Galeria
        });
    }
};
