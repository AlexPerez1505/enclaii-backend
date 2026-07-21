<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        if (Schema::hasColumn('capture_sessions', 'study_id')) {
            DB::statement('ALTER TABLE capture_sessions MODIFY study_id BIGINT UNSIGNED NULL');
        }

        if (Schema::hasColumn('capture_sessions', 'estudio_id')) {
            DB::statement('ALTER TABLE capture_sessions MODIFY estudio_id BIGINT UNSIGNED NULL');
        }

        if (Schema::hasColumn('capture_sessions', 'paciente_id')) {
            DB::statement('ALTER TABLE capture_sessions MODIFY paciente_id BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        if (Schema::hasColumn('capture_sessions', 'study_id')) {
            DB::statement('ALTER TABLE capture_sessions MODIFY study_id BIGINT UNSIGNED NOT NULL');
        }
    }
};
