<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudio_hallazgos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudio_id')->constrained('estudios')->cascadeOnDelete();
            $table->foreignId('hallazgo_id')->constrained('hallazgos')->cascadeOnDelete();
            $table->string('detectado_por');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudio_hallazgos');
    }
};
