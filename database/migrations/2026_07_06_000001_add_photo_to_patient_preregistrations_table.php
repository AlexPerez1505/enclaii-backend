<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patient_preregistrations', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('observaciones');
        });
    }

    public function down(): void
    {
        Schema::table('patient_preregistrations', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};
