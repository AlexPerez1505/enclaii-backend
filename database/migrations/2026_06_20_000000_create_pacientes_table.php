<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();

            $table->string('folio')->unique();
            $table->string('nombre_completo');
            $table->string('identificacion')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->integer('edad')->nullable();

            $table->decimal('peso', 5, 2)->nullable();
            $table->decimal('altura', 4, 2)->nullable();

            $table->enum('sexo', ['femenino', 'masculino', 'otro'])->nullable();

            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();

            $table->string('medico')->nullable();
            $table->string('procedimiento')->nullable();
            $table->string('anestesiologo')->nullable();
            $table->string('referido_por')->nullable();
            $table->string('equipo_utilizado')->nullable();

            $table->text('diagnostico_preliminar')->nullable();
            $table->string('foto')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};