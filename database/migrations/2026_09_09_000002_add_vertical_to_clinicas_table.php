<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinicas', function (Blueprint $table): void {
            $table->string('vertical', 20)->default('medica')->after('nombre');
        });
    }

    public function down(): void
    {
        Schema::table('clinicas', function (Blueprint $table): void {
            $table->dropColumn('vertical');
        });
    }
};
