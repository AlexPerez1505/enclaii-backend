<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('capture_pairing_codes', function (Blueprint $table) {
            if (! Schema::hasColumn('capture_pairing_codes', 'paciente_id')) {
                $table->unsignedBigInteger('paciente_id')->nullable()->after('user_id')->index();
            }

            if (! Schema::hasColumn('capture_pairing_codes', 'estudio_id')) {
                $table->unsignedBigInteger('estudio_id')->nullable()->after('paciente_id')->index();
            }
        });

        if (DB::getDriverName() !== 'sqlite' && Schema::hasColumn('capture_pairing_codes', 'study_id')) {
            DB::statement('ALTER TABLE capture_pairing_codes MODIFY study_id BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        Schema::table('capture_pairing_codes', function (Blueprint $table) {
            if (Schema::hasColumn('capture_pairing_codes', 'paciente_id')) {
                $table->dropColumn('paciente_id');
            }

            if (Schema::hasColumn('capture_pairing_codes', 'estudio_id')) {
                $table->dropColumn('estudio_id');
            }
        });

        if (DB::getDriverName() !== 'sqlite' && Schema::hasColumn('capture_pairing_codes', 'study_id')) {
            DB::statement('ALTER TABLE capture_pairing_codes MODIFY study_id BIGINT UNSIGNED NOT NULL');
        }
    }
};
