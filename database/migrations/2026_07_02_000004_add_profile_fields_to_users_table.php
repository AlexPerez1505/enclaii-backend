<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Información de la cuenta (ya existen de la ejecución parcial anterior)
            if (!Schema::hasColumn('users', 'apellido_paterno'))
                $table->string('apellido_paterno')->nullable()->after('name');
            if (!Schema::hasColumn('users', 'apellido_materno'))
                $table->string('apellido_materno')->nullable()->after('apellido_paterno');
            if (!Schema::hasColumn('users', 'fecha_nacimiento'))
                $table->date('fecha_nacimiento')->nullable()->after('apellido_materno');
            if (!Schema::hasColumn('users', 'sexo'))
                $table->string('sexo')->nullable()->after('fecha_nacimiento');

            // Información profesional
            if (!Schema::hasColumn('users', 'subespecialidad'))
                $table->string('subespecialidad')->nullable()->after('specialty');
            if (!Schema::hasColumn('users', 'universidad'))
                $table->string('universidad')->nullable()->after('subespecialidad');

            // Información de la clínica
            if (!Schema::hasColumn('users', 'clinica_nombre'))
                $table->string('clinica_nombre')->nullable()->after('universidad');
            if (!Schema::hasColumn('users', 'clinica_ciudad'))
                $table->string('clinica_ciudad')->nullable()->after('clinica_nombre');
            if (!Schema::hasColumn('users', 'clinica_direccion'))
                $table->string('clinica_direccion')->nullable()->after('clinica_ciudad');
            if (!Schema::hasColumn('users', 'clinica_codigo_postal'))
                $table->string('clinica_codigo_postal')->nullable()->after('clinica_direccion');
            if (!Schema::hasColumn('users', 'clinica_telefono'))
                $table->string('clinica_telefono')->nullable()->after('clinica_codigo_postal');
            if (!Schema::hasColumn('users', 'clinica_estado'))
                $table->string('clinica_estado')->nullable()->after('clinica_telefono');

            // Información fiscal
            if (!Schema::hasColumn('users', 'rfc'))
                $table->string('rfc')->nullable()->after('clinica_estado');
            if (!Schema::hasColumn('users', 'razon_social'))
                $table->string('razon_social')->nullable()->after('rfc');
            if (!Schema::hasColumn('users', 'regimen_fiscal'))
                $table->string('regimen_fiscal')->nullable()->after('razon_social');
            if (!Schema::hasColumn('users', 'correo_facturacion'))
                $table->string('correo_facturacion')->nullable()->after('regimen_fiscal');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'apellido_paterno', 'apellido_materno', 'fecha_nacimiento', 'sexo',
                'subespecialidad', 'universidad',
                'clinica_nombre', 'clinica_ciudad', 'clinica_direccion',
                'clinica_codigo_postal', 'clinica_telefono', 'clinica_estado',
                'rfc', 'razon_social', 'regimen_fiscal', 'correo_facturacion',
            ]);
        });
    }
};
