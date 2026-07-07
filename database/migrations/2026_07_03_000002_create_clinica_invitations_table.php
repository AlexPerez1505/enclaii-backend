<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinica_invitations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('clinica_id')->constrained('clinicas')->cascadeOnDelete();
            $table->foreignId('invited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('accepted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('email');
            $table->string('rol', 30)->default('medico');
            $table->string('token_hash', 64)->unique();
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->index(['clinica_id', 'email']);
            $table->index(['clinica_id', 'accepted_at', 'revoked_at']);
        });

        Schema::table('clinicas', function (Blueprint $table): void {
            $table->dropUnique('clinicas_codigo_invitacion_unique');
            $table->dropColumn('codigo_invitacion');
        });
    }

    public function down(): void
    {
        Schema::table('clinicas', function (Blueprint $table): void {
            $table->string('codigo_invitacion', 32)->nullable()->unique();
        });

        Schema::dropIfExists('clinica_invitations');
    }
};
