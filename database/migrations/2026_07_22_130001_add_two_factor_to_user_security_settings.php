<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_security_settings', function (Blueprint $table) {
            $table->boolean('two_factor_email_enabled')->default(false)->after('audit_sensitive_actions');
            $table->timestamp('two_factor_email_confirmed_at')->nullable()->after('two_factor_email_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('user_security_settings', function (Blueprint $table) {
            $table->dropColumn(['two_factor_email_enabled', 'two_factor_email_confirmed_at']);
        });
    }
};