<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('launch_promo_codes', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 60)->unique();
            $table->text('token');
            $table->char('token_hash', 64)->unique();
            $table->string('type', 20)->default('launch')->index();
            $table->string('plan', 30)->default('clinica');
            $table->string('interval', 20)->default('month');
            $table->unsignedTinyInteger('trial_months')->default(6);
            $table->string('status', 20)->default('active')->index();
            $table->foreignId('reserved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reserved_at')->nullable();
            $table->foreignId('redeemed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('redeemed_at')->nullable();
            $table->string('checkout_session_id')->nullable()->index();
            $table->string('stripe_subscription_id')->nullable()->index();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('launch_promo_codes');
    }
};
