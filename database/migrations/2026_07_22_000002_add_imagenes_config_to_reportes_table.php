<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('reportes') || Schema::hasColumn('reportes', 'imagenes_config')) {
            return;
        }

        Schema::table('reportes', function (Blueprint $table) {
            $table->json('imagenes_config')->nullable()->after('contenido_html');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('reportes') || ! Schema::hasColumn('reportes', 'imagenes_config')) {
            return;
        }

        Schema::table('reportes', function (Blueprint $table) {
            $table->dropColumn('imagenes_config');
        });
    }
};
