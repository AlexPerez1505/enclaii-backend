<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private array $clinicalTables = [
        'salas',
        'pacientes',
        'citas',
        'estudios',
        'estudio_archivos',
        'predicciones_pre_estudio',
        'estudio_hallazgos',
        'reportes',
        'ia_reportes',
        'whatsapp_messages',
    ];

    public function up(): void
    {
        Schema::create('clinicas', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre');
            $table->string('codigo_invitacion', 32)->unique();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('clinica_id')
                ->nullable()
                ->after('id')
                ->constrained('clinicas');
            $table->string('clinica_rol', 30)->default('medico')->after('clinica_id');
        });

        foreach ($this->clinicalTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->foreignId('clinica_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('clinicas');
            });
        }

        if (DB::table('users')->exists() || DB::table('pacientes')->exists()) {
            $clinicaId = DB::table('clinicas')->insertGetId([
                'nombre' => 'Clínica principal',
                'codigo_invitacion' => strtoupper(Str::random(12)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('users')->update([
                'clinica_id' => $clinicaId,
                'clinica_rol' => 'propietario',
            ]);

            foreach ($this->clinicalTables as $tableName) {
                DB::table($tableName)->update(['clinica_id' => $clinicaId]);
            }
        }

        Schema::table('pacientes', function (Blueprint $table): void {
            $table->dropUnique('pacientes_folio_unique');
            $table->unique(['clinica_id', 'folio']);
        });

        Schema::table('estudios', function (Blueprint $table): void {
            $table->dropUnique('estudios_folio_unique');
            $table->unique(['clinica_id', 'folio']);
        });
    }

    public function down(): void
    {
        Schema::table('estudios', function (Blueprint $table): void {
            $table->dropUnique(['clinica_id', 'folio']);
            $table->unique('folio');
        });

        Schema::table('pacientes', function (Blueprint $table): void {
            $table->dropUnique(['clinica_id', 'folio']);
            $table->unique('folio');
        });

        foreach (array_reverse($this->clinicalTables) as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropConstrainedForeignId('clinica_id');
            });
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('clinica_rol');
            $table->dropConstrainedForeignId('clinica_id');
        });

        Schema::dropIfExists('clinicas');
    }
};


