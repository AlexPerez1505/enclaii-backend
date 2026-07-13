<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bloqueos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinica_id')->constrained()->cascadeOnDelete();
            $table->string('label')->default('Bloqueo de Tiempo');
            $table->date('fecha');
            $table->time('hora');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bloqueos');
    }
};
