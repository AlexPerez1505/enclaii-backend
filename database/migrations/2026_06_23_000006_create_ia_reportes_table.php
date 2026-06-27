<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ia_reportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')
                ->constrained('reportes')
                ->onDelete('cascade');
            $table->json('analisis_ia');
            $table->string('version_modelo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ia_reportes');
    }
};
