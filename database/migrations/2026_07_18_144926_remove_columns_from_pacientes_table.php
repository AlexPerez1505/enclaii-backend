<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = array_values(array_filter(
            ['referido_por', 'equipo_utilizado'],
            fn (string $column) => Schema::hasColumn('pacientes', $column),
        ));

        if ($columns) {
            Schema::table('pacientes', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->string('referido_por')->nullable();
            $table->string('equipo_utilizado')->nullable();
        });
    }
};
