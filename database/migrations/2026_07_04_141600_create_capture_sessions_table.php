<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('capture_sessions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tenant_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('paciente_id')->nullable()->index();
            $table->unsignedBigInteger('estudio_id')->nullable()->index();
            $table->unsignedBigInteger('study_id')->nullable()->index();
            $table->unsignedBigInteger('capture_device_id')->nullable()->index();

            $table->string('status')->default('active');

            $table->string('live_frame_path')->nullable();
            $table->timestamp('live_frame_at')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'study_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capture_sessions');
    }
};
