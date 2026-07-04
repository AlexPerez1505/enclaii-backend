<?php

namespace App\Services;

use App\Models\User;
use RuntimeException;
use Stripe\StripeClient;

class StripeService
{
    private StripeClient $client;

    public function __construct()
    {
        $secret = config('services.stripe.secret');

        if (empty($secret)) {
            throw new RuntimeException('Falta configurar STRIPE_SECRET en el archivo .env.');
        }

        $this->client = new StripeClient($secret);
    }

    /**
     * Devuelve el Price ID para un plan e intervalo, o para almacenamiento.
     * 
     * @param string $plan Plan: 'clinica', 'hospital', 'red_medica', 'empresarial', 'storage_50', 'storage_100'
     * @param string|null $interval Intervalo: 'month', 'quarter', 'year' (solo para planes)
     * @return string|null
     */
    public function priceId(string $plan, ?string $interval = null): ?string
    {
        // Si es almacenamiento, no requiere intervalo
        if (str_starts_with($plan, 'storage_')) {
            return config("services.stripe.storage.$plan");
        }

        // Si es un plan, requiere intervalo
        if ($interval === null) {
            throw new RuntimeException("El plan '$plan' requiere especificar un intervalo (month, quarter, year).");
        }

        return config("services.stripe.plans.$plan.$interval");
    }

    /**
     * Garantiza que el usuario tenga un Customer de Stripe asociado.
     */
    public function resolveCustomer(User $user): string
    {
        if ($user->stripe_customer_id) {
            return $user->stripe_customer_id;
        }

        $customer = $this->client->customers->create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => ['user_id' => (string) $user->id],
        ]);

        $user->forceFill(['stripe_customer_id' => $customer->id])->save();

        return $customer->id;
    }

    /**
     * Crea una sesión de Checkout en modo suscripción para un Price.
     */
    public function createSubscriptionCheckout(User $user, string $priceId, string $successUrl, string $cancelUrl, array $metadata = []): \Stripe\Checkout\Session
    {
        return $this->client->checkout->sessions->create([
            'mode' => 'subscription',
            'customer' => $this->resolveCustomer($user),
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'allow_promotion_codes' => true,
            'metadata' => array_merge(['user_id' => (string) $user->id], $metadata),
        ]);
    }

    /**
     * Crea una sesión de Checkout en modo embedded (para mostrar en modal).
     */
    public function createEmbeddedCheckout(User $user, string $priceId, string $returnUrl, array $metadata = []): \Stripe\Checkout\Session
    {
        return $this->client->checkout->sessions->create([
            'mode' => 'subscription',
            'customer' => $this->resolveCustomer($user),
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'ui_mode' => 'embedded_page',
            'return_url' => $returnUrl,
            'allow_promotion_codes' => true,
            'metadata' => array_merge(['user_id' => (string) $user->id], $metadata),
        ]);
    }

    /**
     * Crea una suscripción en estado incompleto para usar con Payment Element.
     * Devuelve la suscripción con latest_invoice.confirmation_secret expandido.
     */
    public function createSubscriptionIncomplete(User $user, string $priceId, array $metadata = []): \Stripe\Subscription
    {
        return $this->client->subscriptions->create([
            'customer' => $this->resolveCustomer($user),
            'items' => [[
                'price' => $priceId,
            ]],
            'payment_behavior' => 'default_incomplete',
            'payment_settings' => [
                'save_default_payment_method' => 'on_subscription',
            ],
            'expand' => ['latest_invoice.confirmation_secret'],
            'metadata' => array_merge(['user_id' => (string) $user->id], $metadata),
        ]);
    }

    /**
     * Obtiene la suscripción más reciente de un cliente de Stripe.
     */
    public function latestSubscription(string $customerId): ?\Stripe\Subscription
    {
        $subscriptions = $this->client->subscriptions->all([
            'customer' => $customerId,
            'limit' => 1,
        ]);

        return $subscriptions->data[0] ?? null;
    }

    /**
     * Recupera un método de pago por su ID.
     */
    public function retrievePaymentMethod(string $paymentMethodId): \Stripe\PaymentMethod
    {
        return $this->client->paymentMethods->retrieve($paymentMethodId);
    }

    /**
     * Cambia el plan de una suscripción existente (actualiza el price_id).
     * Stripe prorratea automáticamente el cobro. Actualiza también el metadata
     * para que la sincronización posterior lea el plan correcto.
     */
    public function updateSubscriptionPlan(string $subscriptionId, string $newPriceId, array $metadata = []): \Stripe\Subscription
    {
        $subscription = $this->client->subscriptions->retrieve($subscriptionId);

        return $this->client->subscriptions->update($subscriptionId, [
            'items' => [
                [
                    'id' => $subscription->items->data[0]->id,
                    'price' => $newPriceId,
                ],
            ],
            'proration_behavior' => 'create_prorations',
            'metadata' => $metadata,
        ]);
    }

    /**
     * Programa la cancelación de la suscripción al final del ciclo de facturación.
     */
    public function cancelSubscription(string $subscriptionId): \Stripe\Subscription
    {
        return $this->client->subscriptions->update($subscriptionId, [
            'cancel_at_period_end' => true,
        ]);
    }

    /**
     * Reactiva una suscripción que estaba programada para cancelarse.
     */
    public function resumeSubscription(string $subscriptionId): \Stripe\Subscription
    {
        return $this->client->subscriptions->update($subscriptionId, [
            'cancel_at_period_end' => false,
        ]);
    }

    /**
     * Crea un SetupIntent para que el usuario registre/actualice su método de pago
     * sin salir de la aplicación (Stripe Elements).
     */
    public function createSetupIntent(User $user): \Stripe\SetupIntent
    {
        return $this->client->setupIntents->create([
            'customer' => $this->resolveCustomer($user),
            'payment_method_types' => ['card'],
            'usage' => 'off_session',
        ]);
    }

    /**
     * Establece el método de pago por defecto del cliente y de su suscripción.
     */
    public function setDefaultPaymentMethod(User $user, string $paymentMethodId): \Stripe\PaymentMethod
    {
        $customerId = $this->resolveCustomer($user);

        // Asegurar que el método de pago esté asociado al cliente
        $pm = $this->client->paymentMethods->retrieve($paymentMethodId);
        if (empty($pm->customer)) {
            $pm = $this->client->paymentMethods->attach($paymentMethodId, ['customer' => $customerId]);
        }

        // Default a nivel cliente
        $this->client->customers->update($customerId, [
            'invoice_settings' => ['default_payment_method' => $paymentMethodId],
        ]);

        // Default a nivel suscripción
        if ($user->stripe_subscription_id) {
            $this->client->subscriptions->update($user->stripe_subscription_id, [
                'default_payment_method' => $paymentMethodId,
            ]);
        }

        return $pm;
    }

    /**
     * Devuelve el historial de facturas del cliente en Stripe.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listInvoices(User $user, int $limit = 12): array
    {
        if (!$user->stripe_customer_id) {
            return [];
        }

        $invoices = $this->client->invoices->all([
            'customer' => $user->stripe_customer_id,
            'limit' => $limit,
        ]);

        $result = [];
        foreach ($invoices->data as $invoice) {
            $result[] = [
                'id' => $invoice->id,
                'number' => $invoice->number,
                'amount' => $invoice->amount_paid ?: $invoice->amount_due,
                'currency' => strtoupper($invoice->currency),
                'status' => $invoice->status,
                'date' => $invoice->created,
                'pdf' => $invoice->invoice_pdf,
                'url' => $invoice->hosted_invoice_url,
            ];
        }

        return $result;
    }

    /**
     * Construye y verifica un evento de webhook a partir del payload y la firma.
     */
    public function constructWebhookEvent(string $payload, string $signature): \Stripe\Event
    {
        $secret = config('services.stripe.webhook_secret');

        if (empty($secret)) {
            throw new RuntimeException('Falta configurar STRIPE_WEBHOOK_SECRET en el archivo .env.');
        }

        return \Stripe\Webhook::constructEvent($payload, $signature, $secret);
    }

    /**
     * Recupera una suscripción de Stripe por su ID.
     */
    public function retrieveSubscription(string $subscriptionId): \Stripe\Subscription
    {
        return $this->client->subscriptions->retrieve($subscriptionId);
    }

    public function client(): StripeClient
    {
        return $this->client;
    }
}
