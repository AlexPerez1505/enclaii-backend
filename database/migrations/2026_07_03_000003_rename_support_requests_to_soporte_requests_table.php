<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('support_requests', 'soporte_requests');
    }

    public function down(): void
    {
        Schema::rename('soporte_requests', 'support_requests');
    }
};
