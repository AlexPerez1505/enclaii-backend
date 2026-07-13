<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            if (! Schema::hasColumn('pacientes', 'enfermedad')) {
                $table->text('enfermedad')->nullable()->after('diagnostico_preliminar');
            }
            if (! Schema::hasColumn('pacientes', 'alergias')) {
                $table->text('alergias')->nullable()->after('enfermedad');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('pacientes', 'enfermedad')) {
                $columns[] = 'enfermedad';
            }
            if (Schema::hasColumn('pacientes', 'alergias')) {
                $columns[] = 'alergias';
            }
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
