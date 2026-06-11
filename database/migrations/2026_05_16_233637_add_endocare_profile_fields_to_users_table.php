<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('specialty')->nullable()->after('phone');
            $table->string('professional_license')->nullable()->after('specialty');
            $table->string('medical_area')->nullable()->after('professional_license');
            $table->string('position')->nullable()->after('medical_area');
            $table->boolean('profile_completed')->default(false)->after('position');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'specialty',
                'professional_license',
                'medical_area',
                'position',
                'profile_completed',
            ]);
        });
    }
};