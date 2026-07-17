<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estudio_archivos', function (Blueprint $table) {
            $table->dropForeign(['estudio_id']);
        });

        DB::statement('ALTER TABLE estudio_archivos MODIFY estudio_id BIGINT UNSIGNED NULL');

        Schema::table('estudio_archivos', function (Blueprint $table) {
            $table->foreign('estudio_id')
                ->references('id')
                ->on('estudios')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('estudio_archivos', function (Blueprint $table) {
            $table->dropForeign(['estudio_id']);
        });

        DB::statement('ALTER TABLE estudio_archivos MODIFY estudio_id BIGINT UNSIGNED NOT NULL');

        Schema::table('estudio_archivos', function (Blueprint $table) {
            $table->foreign('estudio_id')
                ->references('id')
                ->on('estudios')
                ->cascadeOnDelete();
        });
    }
};
