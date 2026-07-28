<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('capture_video_uploads', function (Blueprint $table) {
            $table->id();

            $table->string('upload_id')->unique();
            $table->unsignedBigInteger('session_id')->index();

            $table->string('filename');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('total_size');
            $table->unsignedInteger('total_chunks');
            $table->json('received_chunks')->nullable();

            $table->string('status')->default('pending');
            $table->string('path')->nullable();
            $table->timestamp('ended_at')->nullable();

            $table->timestamps();

            $table->index(['session_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capture_video_uploads');
    }
};
