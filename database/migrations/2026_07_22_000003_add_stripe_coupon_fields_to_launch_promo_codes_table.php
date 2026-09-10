<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('launch_promo_codes', function (Blueprint $table): void {
            $table->string('stripe_coupon_id')->nullable()->after('trial_months')->index();
            $table->string('stripe_promotion_code_id')->nullable()->after('stripe_coupon_id')->unique();
        });
    }

    public function down(): void
    {
        Schema::table('launch_promo_codes', function (Blueprint $table): void {
            $table->dropUnique(['stripe_promotion_code_id']);
            $table->dropIndex(['stripe_coupon_id']);
            $table->dropColumn(['stripe_coupon_id', 'stripe_promotion_code_id']);
        });
    }
};
