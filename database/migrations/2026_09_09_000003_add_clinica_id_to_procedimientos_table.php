<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Catálogo global heredado (sin clinica_id) que se debe repartir entre las
     * clínicas privadas existentes antes de aplicar el aislamiento por tenant.
     */
    public function up(): void
    {
        Schema::table('procedimientos', function (Blueprint $table) {
            $table->foreignId('clinica_id')
                ->nullable()
                ->after('id')
                ->constrained('clinicas')
                ->cascadeOnDelete();
        });

        Schema::table('procedimientos', function (Blueprint $table) {
            $table->dropUnique('procedimientos_nombre_unique');
        });

        $this->backfillPorClinica();

        Schema::table('procedimientos', function (Blueprint $table) {
            $table->unique(['clinica_id', 'nombre']);
        });
    }

    /**
     * Duplica cada procedimiento global (clinica_id NULL) hacia cada clínica
     * privada existente, para que ninguna clínica pierda su catálogo actual.
     */
    private function backfillPorClinica(): void
    {
        $globales = DB::table('procedimientos')->whereNull('clinica_id')->get();

        if ($globales->isEmpty()) {
            return;
        }

        $clinicaIds = DB::table('clinicas')->where('is_shared', false)->pluck('id');

        foreach ($clinicaIds as $clinicaId) {
            foreach ($globales as $procedimiento) {
                DB::table('procedimientos')->insertOrIgnore([
                    'clinica_id' => $clinicaId,
                    'nombre' => $procedimiento->nombre,
                    'created_at' => $procedimiento->created_at,
                    'updated_at' => now(),
                ]);
            }
        }

        DB::table('procedimientos')->whereNull('clinica_id')->delete();
    }

    public function down(): void
    {
        Schema::table('procedimientos', function (Blueprint $table) {
            $table->dropUnique(['clinica_id', 'nombre']);
        });

        Schema::table('procedimientos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('clinica_id');
        });

        Schema::table('procedimientos', function (Blueprint $table) {
            $table->unique('nombre');
        });
    }
};
