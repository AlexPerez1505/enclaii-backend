<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('soporte_requests')) {
            return;
        }

        Schema::create('soporte_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category', 100);
            $table->string('subject', 255);
            $table->text('description');
            $table->string('status', 50)->default('abierto');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soporte_requests');
    }
};
