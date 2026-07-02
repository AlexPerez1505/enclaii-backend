<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\StripeService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SyncStripeSubscription extends Command
{
    protected $signature = 'stripe:sync {user_id}';
    protected $description = 'Sincronizar suscripción de Stripe con usuario';

    public function handle(StripeService $stripe): int
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);

        if (!$user) {
            $this->error("Usuario no encontrado");
            return 1;
        }

        if (!$user->stripe_customer_id) {
            $this->error("Usuario no tiene stripe_customer_id");
            return 1;
        }

        $this->info("Buscando suscripciones para customer: {$user->stripe_customer_id}");

        try {
            $subscriptions = $stripe->client()->subscriptions->all([
                'customer' => $user->stripe_customer_id,
                'limit' => 1,
            ]);

            if (count($subscriptions->data) === 0) {
                $this->error("No se encontraron suscripciones");
                return 1;
            }

            $subscription = $subscriptions->data[0];
            
            $this->info("Suscripción encontrada: {$subscription->id}");
            $this->info("Estado: {$subscription->status}");

            // Obtener el plan del metadata o del price
            $plan = $subscription->metadata->plan ?? null;
            if (!$plan && isset($subscription->items->data[0])) {
                $priceId = $subscription->items->data[0]->price->id;
                $this->info("Price ID: {$priceId}");
                
                // Intentar deducir el plan del price ID
                $plans = config('services.stripe.plans');
                foreach ($plans as $planKey => $intervals) {
                    foreach ($intervals as $interval => $pid) {
                        if ($pid === $priceId) {
                            $plan = $planKey;
                            break 2;
                        }
                    }
                }
            }

            $data = [
                'stripe_subscription_id' => $subscription->id,
                'subscription_status' => $subscription->status,
                'subscription_renews_at' => !empty($subscription->current_period_end)
                    ? Carbon::createFromTimestamp($subscription->current_period_end)
                    : null,
            ];

            if ($plan) {
                $data['stripe_plan'] = $plan;
            }

            // Obtener método de pago
            if (!empty($subscription->default_payment_method)) {
                try {
                    $paymentMethod = $stripe->client()->paymentMethods->retrieve($subscription->default_payment_method);
                    if ($paymentMethod->type === 'card' && !empty($paymentMethod->card)) {
                        $data['pm_type'] = 'card';
                        $data['pm_last_four'] = $paymentMethod->card->last4;
                        $data['pm_brand'] = $paymentMethod->card->brand;
                        
                        $this->info("Tarjeta: {$paymentMethod->card->brand} ****{$paymentMethod->card->last4}");
                    }
                } catch (\Throwable $e) {
                    $this->warn("No se pudo obtener método de pago: " . $e->getMessage());
                }
            }

            $user->forceFill($data)->save();

            $this->info("✅ Usuario sincronizado correctamente");
            $this->table(
                ['Campo', 'Valor'],
                [
                    ['Plan', $user->stripe_plan],
                    ['Estado', $user->subscription_status],
                    ['Renovación', $user->subscription_renews_at],
                    ['Tarjeta', $user->pm_brand ? "{$user->pm_brand} ****{$user->pm_last_four}" : 'N/A'],
                ]
            );

            return 0;
        } catch (\Throwable $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
