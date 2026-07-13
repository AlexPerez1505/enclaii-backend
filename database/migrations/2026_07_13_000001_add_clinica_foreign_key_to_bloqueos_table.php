<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('bloqueos') || ! Schema::hasTable('clinicas')) {
            return;
        }

        $foreignKeyExists = collect(Schema::getForeignKeys('bloqueos'))
            ->contains(fn ($fk) => in_array('clinica_id', $fk['columns'], true));

        if ($foreignKeyExists) {
            return;
        }

        Schema::table('bloqueos', function (Blueprint $table) {
            $table->foreign('clinica_id')
                ->references('id')
                ->on('clinicas')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('bloqueos')) {
            return;
        }

        Schema::table('bloqueos', function (Blueprint $table) {
            $table->dropForeign(['clinica_id']);
        });
    }
};
