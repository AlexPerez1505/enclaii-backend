<?php

namespace Tests\Feature;

use App\Models\LaunchPromoCode;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

class LaunchPromoRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_promo_registration_reserves_code_and_redirects_to_stripe_checkout(): void
    {
        [$promo, $token] = $this->promoCode();
        $checkout = \Stripe\Checkout\Session::constructFrom([
            'id' => 'cs_promo_trial',
            'url' => 'https://checkout.stripe.test/promo-trial',
        ]);

        $stripe = Mockery::mock(StripeService::class);
        $stripe->shouldReceive('priceId')
            ->once()
            ->with('clinica', 'month')
            ->andReturn('price_clinica_month');
        $stripe->shouldReceive('createPromoTrialCheckout')
            ->once()
            ->withArgs(function (
                User $user,
                string $priceId,
                int $trialEnd,
                string $successUrl,
                string $cancelUrl,
                array $metadata,
            ) use ($promo): bool {
                return $user->email === 'promo@example.com'
                    && $priceId === 'price_clinica_month'
                    && $trialEnd > now()->addMonths(5)->timestamp
                    && str_contains($successUrl, 'session_id={CHECKOUT_SESSION_ID}')
                    && str_contains($cancelUrl, '/registro-promocion/')
                    && $metadata['type'] === 'promo_trial'
                    && $metadata['promo_code_id'] === (string) $promo->id
                    && $metadata['promo_code'] === $promo->code;
            })
            ->andReturn($checkout);
        $this->app->instance(StripeService::class, $stripe);

        $this->post(route('promo.register.store', ['token' => $token]), [
            'name' => 'Usuario Promo',
            'email' => 'promo@example.com',
            'password' => 'SecurePassword1',
            'password_confirmation' => 'SecurePassword1',
        ])->assertRedirect('https://checkout.stripe.test/promo-trial');

        $user = User::query()->where('email', 'promo@example.com')->firstOrFail();
        $promo->refresh();

        $this->assertAuthenticatedAs($user);
        $this->assertSame(LaunchPromoCode::STATUS_RESERVED, $promo->status);
        $this->assertSame($user->id, $promo->reserved_by);
        $this->assertSame('cs_promo_trial', $promo->checkout_session_id);
    }

    public function test_reserved_promo_code_cannot_create_a_second_account(): void
    {
        [$promo, $token] = $this->promoCode();
        $user = User::create([
            'name' => 'Reservado',
            'email' => 'reserved@example.com',
            'password' => 'SecurePassword1',
        ]);
        $promo->reserveFor($user);

        $stripe = Mockery::mock(StripeService::class);
        $stripe->shouldNotReceive('priceId');
        $stripe->shouldNotReceive('createPromoTrialCheckout');
        $this->app->instance(StripeService::class, $stripe);

        $this->post(route('promo.register.store', ['token' => $token]), [
            'name' => 'Otro Usuario',
            'email' => 'other@example.com',
            'password' => 'SecurePassword1',
            'password_confirmation' => 'SecurePassword1',
        ])->assertSessionHasErrors('promo');

        $this->assertDatabaseMissing('users', ['email' => 'other@example.com']);
    }

    public function test_regular_registration_with_promo_redirects_to_local_payment_modal(): void
    {
        [$promo] = $this->promoCode([
            'code' => 'ENCLAII-LAUNCH-001',
            'stripe_coupon_id' => 'coupon_launch_6m',
            'stripe_promotion_code_id' => 'promo_launch_001',
        ]);

        $stripe = Mockery::mock(StripeService::class);
        $stripe->shouldNotReceive('priceId');
        $stripe->shouldNotReceive('createPromoCouponCheckout');
        $this->app->instance(StripeService::class, $stripe);

        $this->post(route('register.post'), [
            'name' => 'Usuario Coupon',
            'email' => 'coupon@example.com',
            'password' => 'SecurePassword1',
            'password_confirmation' => 'SecurePassword1',
            'promo_code' => ' enclaii-launch-001 ',
        ])
            ->assertRedirect(route('plan.only'))
            ->assertSessionHas('promo_checkout');

        $user = User::query()->where('email', 'coupon@example.com')->firstOrFail();
        $promo->refresh();

        $this->assertAuthenticatedAs($user);
        $this->assertSame(LaunchPromoCode::STATUS_RESERVED, $promo->status);
        $this->assertSame($user->id, $promo->reserved_by);
        $this->assertNull($promo->checkout_session_id);
    }

    public function test_regular_registration_rejects_unknown_coupon_code(): void
    {
        $this->post(route('register.post'), [
            'name' => 'Usuario Sin Coupon',
            'email' => 'unknown-coupon@example.com',
            'password' => 'SecurePassword1',
            'password_confirmation' => 'SecurePassword1',
            'promo_code' => 'NO-EXISTE',
        ])->assertSessionHasErrors('promo_code');

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'unknown-coupon@example.com']);
    }

    public function test_regular_registration_rejects_coupon_not_linked_to_stripe(): void
    {
        $this->promoCode(['code' => 'ENCLAII-LAUNCH-099']);

        $this->post(route('register.post'), [
            'name' => 'Usuario Sin Stripe',
            'email' => 'not-linked@example.com',
            'password' => 'SecurePassword1',
            'password_confirmation' => 'SecurePassword1',
            'promo_code' => 'ENCLAII-LAUNCH-099',
        ])->assertSessionHasErrors('promo_code');

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'not-linked@example.com']);
    }

    public function test_stripe_webhook_marks_promo_redeemed_and_activates_trial(): void
    {
        [$promo] = $this->promoCode();
        $user = User::create([
            'name' => 'Promo Activa',
            'email' => 'active-promo@example.com',
            'password' => 'SecurePassword1',
            'stripe_customer_id' => 'cus_promo',
        ]);
        $promo->reserveFor($user);

        $subscription = \Stripe\Subscription::constructFrom([
            'id' => 'sub_promo_trial',
            'customer' => 'cus_promo',
            'status' => 'trialing',
            'current_period_end' => now()->addMonths(6)->timestamp,
            'metadata' => [
                'user_id' => (string) $user->id,
                'type' => 'promo_trial',
                'promo_code_id' => (string) $promo->id,
                'plan' => 'clinica',
            ],
        ]);
        $event = \Stripe\Event::constructFrom([
            'id' => 'evt_promo_trial',
            'type' => 'customer.subscription.created',
            'data' => ['object' => $subscription],
        ]);

        $stripe = Mockery::mock(StripeService::class);
        $stripe->shouldReceive('constructWebhookEvent')
            ->once()
            ->andReturn($event);
        $this->app->instance(StripeService::class, $stripe);

        $this->post(route('webhooks.stripe'), [], ['Stripe-Signature' => 'test'])
            ->assertOk();

        $user->refresh();
        $promo->refresh();

        $this->assertSame('trialing', $user->subscription_status);
        $this->assertSame('clinica', $user->stripe_plan);
        $this->assertTrue($user->subscribed());
        $this->assertSame(LaunchPromoCode::STATUS_REDEEMED, $promo->status);
        $this->assertSame($user->id, $promo->redeemed_by);
        $this->assertSame('sub_promo_trial', $promo->stripe_subscription_id);
    }

    public function test_checkout_completed_webhook_marks_promo_redeemed(): void
    {
        [$promo] = $this->promoCode();
        $user = User::create([
            'name' => 'Checkout Promo',
            'email' => 'checkout-promo@example.com',
            'password' => 'SecurePassword1',
            'stripe_customer_id' => 'cus_checkout_promo',
        ]);
        $promo->reserveFor($user);

        $session = \Stripe\Checkout\Session::constructFrom([
            'id' => 'cs_checkout_promo',
            'customer' => 'cus_checkout_promo',
            'subscription' => 'sub_checkout_promo',
            'metadata' => [
                'user_id' => (string) $user->id,
                'type' => 'promo_trial',
                'promo_code_id' => (string) $promo->id,
                'promo_code' => $promo->code,
                'plan' => 'clinica',
                'interval' => 'month',
            ],
        ]);
        $subscription = \Stripe\Subscription::constructFrom([
            'id' => 'sub_checkout_promo',
            'customer' => 'cus_checkout_promo',
            'status' => 'trialing',
            'current_period_end' => now()->addMonths(6)->timestamp,
            'metadata' => [
                'user_id' => (string) $user->id,
                'type' => 'promo_trial',
                'promo_code_id' => (string) $promo->id,
                'plan' => 'clinica',
            ],
        ]);
        $event = \Stripe\Event::constructFrom([
            'id' => 'evt_checkout_promo',
            'type' => 'checkout.session.completed',
            'data' => ['object' => $session],
        ]);

        $stripe = Mockery::mock(StripeService::class);
        $stripe->shouldReceive('constructWebhookEvent')
            ->once()
            ->andReturn($event);
        $stripe->shouldReceive('retrieveSubscription')
            ->once()
            ->with('sub_checkout_promo')
            ->andReturn($subscription);
        $this->app->instance(StripeService::class, $stripe);

        $this->post(route('webhooks.stripe'), [], ['Stripe-Signature' => 'test'])
            ->assertOk();

        $user->refresh();
        $promo->refresh();

        $this->assertSame('trialing', $user->subscription_status);
        $this->assertSame('clinica', $user->stripe_plan);
        $this->assertSame(LaunchPromoCode::STATUS_REDEEMED, $promo->status);
        $this->assertSame('sub_checkout_promo', $promo->stripe_subscription_id);
    }

    public function test_checkout_completed_webhook_marks_coupon_promo_redeemed(): void
    {
        [$promo] = $this->promoCode([
            'stripe_coupon_id' => 'coupon_launch_6m',
            'stripe_promotion_code_id' => 'promo_launch_001',
        ]);
        $user = User::create([
            'name' => 'Checkout Coupon',
            'email' => 'checkout-coupon@example.com',
            'password' => 'SecurePassword1',
            'stripe_customer_id' => 'cus_checkout_coupon',
        ]);
        $promo->reserveFor($user);

        $session = \Stripe\Checkout\Session::constructFrom([
            'id' => 'cs_checkout_coupon',
            'customer' => 'cus_checkout_coupon',
            'subscription' => 'sub_checkout_coupon',
            'metadata' => [
                'user_id' => (string) $user->id,
                'type' => 'promo_coupon',
                'promo_code_id' => (string) $promo->id,
                'promo_code' => $promo->code,
                'plan' => 'clinica',
                'interval' => 'month',
            ],
        ]);
        $subscription = \Stripe\Subscription::constructFrom([
            'id' => 'sub_checkout_coupon',
            'customer' => 'cus_checkout_coupon',
            'status' => 'active',
            'current_period_end' => now()->addMonth()->timestamp,
            'metadata' => [
                'user_id' => (string) $user->id,
                'type' => 'promo_coupon',
                'promo_code_id' => (string) $promo->id,
                'plan' => 'clinica',
            ],
        ]);
        $event = \Stripe\Event::constructFrom([
            'id' => 'evt_checkout_coupon',
            'type' => 'checkout.session.completed',
            'data' => ['object' => $session],
        ]);

        $stripe = Mockery::mock(StripeService::class);
        $stripe->shouldReceive('constructWebhookEvent')
            ->once()
            ->andReturn($event);
        $stripe->shouldReceive('retrieveSubscription')
            ->once()
            ->with('sub_checkout_coupon')
            ->andReturn($subscription);
        $this->app->instance(StripeService::class, $stripe);

        $this->post(route('webhooks.stripe'), [], ['Stripe-Signature' => 'test'])
            ->assertOk();

        $user->refresh();
        $promo->refresh();

        $this->assertSame('active', $user->subscription_status);
        $this->assertSame('clinica', $user->stripe_plan);
        $this->assertSame(LaunchPromoCode::STATUS_REDEEMED, $promo->status);
        $this->assertSame('sub_checkout_coupon', $promo->stripe_subscription_id);
    }

    public function test_promo_payment_modal_creates_subscription_and_redeems_coupon(): void
    {
        [$promo] = $this->promoCode([
            'stripe_coupon_id' => 'coupon_launch_6m',
            'stripe_promotion_code_id' => 'promo_launch_001',
        ]);
        $user = User::create([
            'name' => 'Modal Coupon',
            'email' => 'modal-coupon@example.com',
            'password' => 'SecurePassword1',
            'stripe_customer_id' => 'cus_modal_coupon',
        ]);
        $promo->reserveFor($user);

        $paymentMethod = \Stripe\PaymentMethod::constructFrom([
            'id' => 'pm_card_visa',
            'type' => 'card',
            'card' => [
                'brand' => 'visa',
                'last4' => '4242',
            ],
        ]);
        $subscription = \Stripe\Subscription::constructFrom([
            'id' => 'sub_modal_coupon',
            'customer' => 'cus_modal_coupon',
            'status' => 'active',
            'current_period_end' => now()->addMonth()->timestamp,
            'default_payment_method' => 'pm_card_visa',
            'metadata' => [
                'user_id' => (string) $user->id,
                'type' => 'promo_coupon',
                'promo_code_id' => (string) $promo->id,
                'plan' => 'clinica',
            ],
        ]);

        $stripe = Mockery::mock(StripeService::class);
        $stripe->shouldReceive('priceId')
            ->once()
            ->with('clinica', 'month')
            ->andReturn('price_clinica_month');
        $stripe->shouldReceive('setDefaultPaymentMethod')
            ->once()
            ->withArgs(fn (User $candidate, string $paymentMethodId): bool => $candidate->is($user)
                && $paymentMethodId === 'pm_card_visa')
            ->andReturn($paymentMethod);
        $stripe->shouldReceive('createPromoCouponSubscription')
            ->once()
            ->withArgs(function (
                User $candidate,
                string $priceId,
                string $promotionCodeId,
                string $paymentMethodId,
                array $metadata,
            ) use ($user, $promo): bool {
                return $candidate->is($user)
                    && $priceId === 'price_clinica_month'
                    && $promotionCodeId === 'promo_launch_001'
                    && $paymentMethodId === 'pm_card_visa'
                    && $metadata['type'] === 'promo_coupon'
                    && $metadata['promo_code_id'] === (string) $promo->id
                    && $metadata['discount_months'] === '6';
            })
            ->andReturn($subscription);
        $stripe->shouldReceive('retrievePaymentMethod')
            ->once()
            ->with('pm_card_visa')
            ->andReturn($paymentMethod);
        $this->app->instance(StripeService::class, $stripe);

        $this->actingAs($user)
            ->postJson(route('stripe.promo.subscribe'), [
                'promo_code_id' => $promo->id,
                'payment_method' => 'pm_card_visa',
            ])
            ->assertOk()
            ->assertJsonPath('success', true);

        $user->refresh();
        $promo->refresh();

        $this->assertSame('active', $user->subscription_status);
        $this->assertSame('clinica', $user->stripe_plan);
        $this->assertSame('card', $user->pm_type);
        $this->assertSame('4242', $user->pm_last_four);
        $this->assertSame(LaunchPromoCode::STATUS_REDEEMED, $promo->status);
        $this->assertSame('sub_modal_coupon', $promo->stripe_subscription_id);
    }

    public function test_unsubscribed_promo_user_can_create_setup_intent_for_local_modal(): void
    {
        $user = User::create([
            'name' => 'Setup Promo',
            'email' => 'setup-promo@example.com',
            'password' => 'SecurePassword1',
        ]);
        $intent = \Stripe\SetupIntent::constructFrom([
            'id' => 'seti_promo',
            'client_secret' => 'seti_promo_secret',
        ]);

        $stripe = Mockery::mock(StripeService::class);
        $stripe->shouldReceive('createSetupIntent')
            ->once()
            ->withArgs(fn (User $candidate): bool => $candidate->is($user))
            ->andReturn($intent);
        $this->app->instance(StripeService::class, $stripe);

        $this->actingAs($user)
            ->getJson(route('stripe.setup.intent'))
            ->assertOk()
            ->assertJsonPath('clientSecret', 'seti_promo_secret');
    }

    public function test_expired_trialing_user_is_not_subscribed(): void
    {
        $user = User::create([
            'name' => 'Trial Expirado',
            'email' => 'expired-trial@example.com',
            'password' => 'SecurePassword1',
            'stripe_plan' => 'clinica',
            'subscription_status' => 'trialing',
            'subscription_renews_at' => now()->subDay(),
        ]);

        $this->assertFalse($user->subscribed());
    }

    private function promoCode(array $overrides = []): array
    {
        $token = Str::random(64);
        $promo = LaunchPromoCode::create(array_merge([
            'code' => 'ENCLAII-LAUNCH-'.Str::upper(Str::random(6)),
            'token' => $token,
            'token_hash' => hash('sha256', $token),
            'type' => LaunchPromoCode::TYPE_LAUNCH,
            'plan' => 'clinica',
            'interval' => 'month',
            'trial_months' => 6,
            'status' => LaunchPromoCode::STATUS_ACTIVE,
        ], $overrides));

        return [$promo, $token];
    }
}
