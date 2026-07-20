<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anestesiologos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinica_id')
                ->nullable()
                ->constrained('clinicas')
                ->nullOnDelete();
            $table->string('nombres');
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->string('especialidad')->nullable();
            $table->string('cedula_profesional')->nullable();
            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anestesiologos');
    }
};
