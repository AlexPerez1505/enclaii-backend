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
        $table->foreignId('clinica_id')->constrained('clinicas')->cascadeOnDelete();
        $table->boolean('activa')->default(true);
    });
}

    public function down(): void
    {
        Schema::table('salas', function (Blueprint $table) {
            $table->dropForeign(['clinica_id']);
            $table->dropColumn(['clinica_id', 'activa']);
        });
    }
};
