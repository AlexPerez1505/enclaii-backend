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
     *
     * IMPORTANTE: estudio_hallazgos.hallazgo_id tiene cascadeOnDelete(), así que
     * antes de borrar los hallazgos globales hay que reapuntar cada pivote
     * existente a la copia correspondiente de la clínica de su estudio, para no
     * perder hallazgos clínicos ya registrados.
     */
    public function up(): void
    {
        Schema::table('hallazgos', function (Blueprint $table) {
            $table->foreignId('clinica_id')
                ->nullable()
                ->after('id')
                ->constrained('clinicas')
                ->cascadeOnDelete();
        });

        // El código CIE-10 era único de forma global; ahora cada clínica puede
        // tener su propio catálogo (relevante también para clínicas veterinarias
        // que no usan CIE-10 y dejan este campo en null).
        Schema::table('hallazgos', function (Blueprint $table) {
            $table->dropUnique('hallazgos_codigo_cie_unique');
        });

        $this->backfillPorClinica();

        Schema::table('hallazgos', function (Blueprint $table) {
            $table->unique(['clinica_id', 'nombre']);
        });
    }

    private function backfillPorClinica(): void
    {
        $globales = DB::table('hallazgos')->whereNull('clinica_id')->get();

        if ($globales->isEmpty()) {
            return;
        }

        $clinicaIds = DB::table('clinicas')->where('is_shared', false)->pluck('id');

        // clinica_id => [hallazgo_global_id => nuevo_hallazgo_id]
        $mapaNuevosIds = [];

        foreach ($clinicaIds as $clinicaId) {
            foreach ($globales as $hallazgo) {
                $nuevoId = DB::table('hallazgos')->insertGetId([
                    'clinica_id' => $clinicaId,
                    'nombre' => $hallazgo->nombre,
                    'codigo_cie' => $hallazgo->codigo_cie,
                    'es_critico' => $hallazgo->es_critico,
                    'created_at' => $hallazgo->created_at,
                    'updated_at' => now(),
                ]);

                $mapaNuevosIds[$clinicaId][$hallazgo->id] = $nuevoId;
            }
        }

        // Reapuntar cada estudio_hallazgo existente a la copia de su propia clínica
        // (se resuelve la clínica a través del estudio al que pertenece el pivote).
        DB::table('estudio_hallazgos as eh')
            ->join('estudios as e', 'e.id', '=', 'eh.estudio_id')
            ->whereIn('eh.hallazgo_id', $globales->pluck('id'))
            ->select('eh.id as pivot_id', 'eh.hallazgo_id as hallazgo_global_id', 'e.clinica_id as clinica_id')
            ->get()
            ->each(function ($row) use ($mapaNuevosIds) {
                $nuevoId = $mapaNuevosIds[$row->clinica_id][$row->hallazgo_global_id] ?? null;

                if ($nuevoId) {
                    DB::table('estudio_hallazgos')
                        ->where('id', $row->pivot_id)
                        ->update(['hallazgo_id' => $nuevoId]);
                }
            });

        DB::table('hallazgos')->whereNull('clinica_id')->delete();
    }

    public function down(): void
    {
        Schema::table('hallazgos', function (Blueprint $table) {
            $table->dropUnique(['clinica_id', 'nombre']);
        });

        Schema::table('hallazgos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('clinica_id');
        });

        Schema::table('hallazgos', function (Blueprint $table) {
            $table->unique('codigo_cie');
        });
    }
};
