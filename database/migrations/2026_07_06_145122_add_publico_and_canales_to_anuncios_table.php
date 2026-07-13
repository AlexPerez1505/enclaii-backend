<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('anuncios', function (Blueprint $table) {
            if (! Schema::hasColumn('anuncios', 'publico_objetivo')) {
                $table->string('publico_objetivo')->default('todos')->after('tipo');
            }
            if (! Schema::hasColumn('anuncios', 'canales')) {
                $table->json('canales')->nullable()->after('publico_objetivo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anuncios', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('anuncios', 'publico_objetivo')) {
                $columns[] = 'publico_objetivo';
            }
            if (Schema::hasColumn('anuncios', 'canales')) {
                $columns[] = 'canales';
            }
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
