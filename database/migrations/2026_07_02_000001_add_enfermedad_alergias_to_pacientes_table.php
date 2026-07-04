<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->text('enfermedad')->nullable()->after('diagnostico_preliminar');
            $table->text('alergias')->nullable()->after('enfermedad');
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn(['enfermedad', 'alergias']);
        });
    }
};
