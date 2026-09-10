<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('capture_sessions', function (Blueprint $table) {
            if (! Schema::hasColumn('capture_sessions', 'paciente_id')) {
                $table->unsignedBigInteger('paciente_id')->nullable()->after('user_id')->index();
            }

            if (! Schema::hasColumn('capture_sessions', 'estudio_id')) {
                $table->unsignedBigInteger('estudio_id')->nullable()->after('paciente_id')->index();
            }
        });

        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        if (Schema::hasColumn('capture_sessions', 'study_id')) {
            DB::statement('ALTER TABLE capture_sessions MODIFY study_id BIGINT UNSIGNED NULL');
        }

        if (Schema::hasColumn('capture_sessions', 'capture_device_id')) {
            DB::statement('ALTER TABLE capture_sessions MODIFY capture_device_id BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        Schema::table('capture_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('capture_sessions', 'estudio_id')) {
                $table->dropColumn('estudio_id');
            }

            if (Schema::hasColumn('capture_sessions', 'paciente_id')) {
                $table->dropColumn('paciente_id');
            }
        });

        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        if (Schema::hasColumn('capture_sessions', 'study_id')) {
            DB::statement('ALTER TABLE capture_sessions MODIFY study_id BIGINT UNSIGNED NOT NULL');
        }

        if (Schema::hasColumn('capture_sessions', 'capture_device_id')) {
            DB::statement('ALTER TABLE capture_sessions MODIFY capture_device_id BIGINT UNSIGNED NOT NULL');
        }
    }
};
