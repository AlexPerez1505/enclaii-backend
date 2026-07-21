<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('salas', function (Blueprint $table) {
        if (!Schema::hasColumn('salas', 'clinica_id')) {
            $table->foreignId('clinica_id')->constrained('clinicas')->cascadeOnDelete();
        }
        if (!Schema::hasColumn('salas', 'activa')) {
            $table->boolean('activa')->default(true);
        }
    });
}

    public function down(): void
    {
        Schema::table('salas', function (Blueprint $table) {
            if (Schema::hasColumn('salas', 'clinica_id')) {
                $table->dropForeign(['clinica_id']);
                $table->dropColumn('clinica_id');
            }
            if (Schema::hasColumn('salas', 'activa')) {
                $table->dropColumn('activa');
            }
        });
    }
};
