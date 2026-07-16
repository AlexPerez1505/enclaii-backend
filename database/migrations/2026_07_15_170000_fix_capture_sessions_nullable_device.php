<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('capture_sessions', 'capture_device_id')) {
            DB::statement('ALTER TABLE capture_sessions MODIFY capture_device_id BIGINT UNSIGNED NULL');
        }

        if (! Schema::hasColumn('capture_sessions', 'paciente_id')) {
            Schema::table('capture_sessions', function ($table) {
                $table->unsignedBigInteger('paciente_id')->nullable()->index();
            });
        }

        if (! Schema::hasColumn('capture_sessions', 'estudio_id')) {
            Schema::table('capture_sessions', function ($table) {
                $table->unsignedBigInteger('estudio_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('capture_sessions', 'capture_device_id')) {
            DB::statement('ALTER TABLE capture_sessions MODIFY capture_device_id BIGINT UNSIGNED NOT NULL');
        }
    }
};
