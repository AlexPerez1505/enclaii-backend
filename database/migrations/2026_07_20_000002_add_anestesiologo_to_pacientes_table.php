<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pacientes', 'anestesiologo')) {
            Schema::table('pacientes', function (Blueprint $table) {
                $table->string('anestesiologo')->nullable()->after('procedimiento');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pacientes', 'anestesiologo')) {
            Schema::table('pacientes', function (Blueprint $table) {
                $table->dropColumn('anestesiologo');
            });
        }
    }
};
