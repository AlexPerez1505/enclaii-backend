<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_registration_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinica_id')->constrained('clinicas')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->text('token');
            $table->char('token_hash', 64)->unique();
            $table->string('status', 20)->default('active')->index();
            $table->timestamp('expires_at')->index();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
        });

        Schema::create('patient_preregistrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinica_id')->constrained('clinicas')->cascadeOnDelete();
            $table->foreignId('registration_link_id')
                ->unique()
                ->constrained('patient_registration_links')
                ->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('pacientes')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->default('pending')->index();
            $table->string('nombre_completo');
            $table->string('identificacion')->nullable();
            $table->date('fecha_nacimiento');
            $table->unsignedSmallInteger('edad')->nullable();
            $table->decimal('peso', 5, 2)->nullable();
            $table->decimal('altura', 4, 2)->nullable();
            $table->string('sexo', 20)->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono', 50);
            $table->string('email')->nullable();
            $table->string('procedimiento')->nullable();
            $table->text('motivo_consulta')->nullable();
            $table->text('alergias')->nullable();
            $table->text('enfermedades')->nullable();
            $table->text('medicamentos_actuales')->nullable();
            $table->text('antecedentes_medicos')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamp('consent_accepted_at');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['clinica_id', 'status', 'created_at'], 'patient_prereg_clinic_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_preregistrations');
        Schema::dropIfExists('patient_registration_links');
    }
};
