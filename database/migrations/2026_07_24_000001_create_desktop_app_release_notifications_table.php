<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desktop_app_release_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('version')->unique();
            $table->string('installer_path');
            $table->unsignedInteger('target_count')->default(0);
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desktop_app_release_notifications');
    }
};
