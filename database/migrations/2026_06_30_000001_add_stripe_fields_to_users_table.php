<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'stripe_customer_id')) {
                $table->string('stripe_customer_id')->nullable()->index()->after('settings');
            }
            if (!Schema::hasColumn('users', 'stripe_subscription_id')) {
                $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');
            }
            if (!Schema::hasColumn('users', 'stripe_plan')) {
                $table->string('stripe_plan')->nullable()->after('stripe_subscription_id');
            }
            if (!Schema::hasColumn('users', 'subscription_status')) {
                $table->string('subscription_status')->nullable()->after('stripe_plan');
            }
            if (!Schema::hasColumn('users', 'subscription_renews_at')) {
                $table->timestamp('subscription_renews_at')->nullable()->after('subscription_status');
            }
            if (!Schema::hasColumn('users', 'pm_type')) {
                $table->string('pm_type')->nullable()->after('subscription_renews_at');
            }
            if (!Schema::hasColumn('users', 'pm_last_four')) {
                $table->string('pm_last_four', 4)->nullable()->after('pm_type');
            }
            if (!Schema::hasColumn('users', 'pm_brand')) {
                $table->string('pm_brand')->nullable()->after('pm_last_four');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_customer_id',
                'stripe_subscription_id',
                'stripe_plan',
                'subscription_status',
                'subscription_renews_at',
                'pm_type',
                'pm_last_four',
                'pm_brand',
            ]);
        });
    }
};
