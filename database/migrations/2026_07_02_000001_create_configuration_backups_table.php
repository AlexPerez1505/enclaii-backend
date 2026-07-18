<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuration_backups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->uuid('uuid')->unique();
            $table->string('name', 100);
            $table->string('type', 20)->default('manual');
            $table->unsignedSmallInteger('version')->default(1);
            $table->json('scope');
            $table->longText('payload');
            $table->string('status', 20)->default('completed');
            $table->unsignedInteger('size')->default(0);
            $table->timestamp('restored_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuration_backups');
    }
};



