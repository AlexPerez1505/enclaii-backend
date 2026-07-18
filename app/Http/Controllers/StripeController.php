<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

class StripeController extends Controller
{
    /**
     * Claves de plan/almacenamiento permitidas para el checkout.
     */
    private const PLANES_VALIDOS = [
        'clinica',
        'hospital',
        'red_medica',
        'empresarial',
        'storage_50',
        'storage_100',
    ];

    /**
     * Intervalos de facturación válidos para planes.
     */
    private const INTERVALOS_VALIDOS = ['month', 'quarter', 'year'];

    /**
     * Crea una sesión de Stripe Checkout y redirige al usuario a Stripe.
     */
    public function checkout(Request $request, StripeService $stripe): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'plan' => ['required', 'string', 'in:' . implode(',', self::PLANES_VALIDOS)],
            'interval' => ['nullable', 'string', 'in:' . implode(',', self::INTERVALOS_VALIDOS)],
        ]);

        $plan = $validated['plan'];
        $interval = $validated['interval'] ?? null;

        // Si es almacenamiento, no requiere intervalo
        $isStorage = str_starts_with($plan, 'storage_');

        // Si es un plan (no almacenamiento), requiere intervalo
        if (!$isStorage && !$interval) {
            return back()->with('error', 'Debes seleccionar un intervalo de facturación (mensual, trimestral o anual).');
        }

        try {
            $priceId = $stripe->priceId($plan, $interval);
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        if (empty($priceId)) {
            return back()->with('error', 'Este plan/intervalo aún no tiene un precio configurado en Stripe.');
        }

        try {
            $session = $stripe->createSubscriptionCheckout(
                $request->user(),
                $priceId,
                route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                route('stripe.cancel'),
                [
                    'plan' => $plan,
                    'interval' => $interval ?? 'N/A',
                ],
            );
        } catch (Throwable $e) {
            Log::error('Stripe checkout error', ['message' => $e->getMessage()]);

            return back()->with('error', 'No se pudo iniciar el pago: ' . $e->getMessage());
        }

        return redirect()->away($session->url);
    }

    /**
     * Inicia el pago mensual de una cuenta adicional para Red Médica.
     */
    public function memberAddonCheckout(Request $request, StripeService $stripe): RedirectResponse
    {
        $user = $request->user()->billingUser();

        if ($user->stripe_plan !== 'red_medica') {
            return back()->with('error', 'Las cuentas adicionales están disponibles para Red Médica.');
        }

        $pendingCount = $user->clinica->invitations()
            ->whereNull('accepted_at')
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->count();
        $memberCount = $user->clinica->usuarios()->count();

        if (($memberCount + $pendingCount) < $user->clinicMemberLimit()) {
            return back()->with('info', 'Todavía tienes lugares disponibles en tu plan.');
        }

        try {
            $session = $stripe->createMemberAddonCheckout(
                $user,
                route('stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
                route('stripe.cancel'),
            );
        } catch (Throwable $e) {
            Log::error('Stripe member addon checkout error', ['message' => $e->getMessage()]);

            return back()->with('error', 'No se pudo iniciar el pago: '.$e->getMessage());
        }

        return redirect()->away($session->url);
    }

    /**
     * Crea una sesión de Checkout Embedded y devuelve el client_secret.
     */
    public function checkoutEmbedded(Request $request, StripeService $stripe): JsonResponse
    {
        $validated = $request->validate([
            'plan' => ['required', 'string', 'in:' . implode(',', self::PLANES_VALIDOS)],
            'interval' => ['nullable', 'string', 'in:' . implode(',', self::INTERVALOS_VALIDOS)],
        ]);

        $plan = $validated['plan'];
        $interval = $validated['interval'] ?? null;

        // Si es almacenamiento, no requiere intervalo
        $isStorage = str_starts_with($plan, 'storage_');

        // Si es un plan (no almacenamiento), requiere intervalo
        if (!$isStorage && !$interval) {
            return response()->json(['error' => 'Debes seleccionar un intervalo de facturación (mensual, trimestral o anual).'], 400);
        }

        try {
            $priceId = $stripe->priceId($plan, $interval);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        if (empty($priceId)) {
            return response()->json(['error' => 'Este plan/intervalo aún no tiene un precio configurado en Stripe.'], 400);
        }

        try {
            $session = $stripe->createEmbeddedCheckout(
                $request->user(),
                $priceId,
                route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                [
                    'plan' => $plan,
                    'interval' => $interval ?? 'N/A',
                ],
            );
        } catch (Throwable $e) {
            Log::error('Stripe embedded checkout error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudo iniciar el pago: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'clientSecret' => $session->client_secret,
        ]);
    }

    /**
     * Devuelve el historial de pagos (facturas) del usuario desde Stripe.
     */
    public function invoices(Request $request, StripeService $stripe): JsonResponse
    {
        $user = $request->user();

        try {
            $invoices = $stripe->listInvoices($user, 12);
        } catch (Throwable $e) {
            Log::error('Stripe list invoices error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudo obtener el historial de pagos.'], 500);
        }

        return response()->json(['invoices' => $invoices]);
    }

    /**
     * Cancela la suscripción al final del ciclo de facturación (sin redirigir a Stripe).
     */
    public function cancelSubscription(Request $request, StripeService $stripe): JsonResponse
    {
        $user = $request->user();

        if (!$user->stripe_subscription_id) {
            return response()->json(['error' => 'No tienes una suscripción activa.'], 400);
        }

        try {
            $subscription = $stripe->cancelSubscription($user->stripe_subscription_id);
            $this->syncUserFromSubscription($user, $subscription, $stripe);
        } catch (Throwable $e) {
            Log::error('Stripe cancel subscription error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudo cancelar el plan: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tu plan se cancelará al finalizar el ciclo de facturación.',
            'cancelAt' => $user->fresh()->subscription_cancel_at?->format('d/m/Y'),
        ]);
    }

    /**
     * Reactiva una suscripción que estaba programada para cancelarse.
     */
    public function resumeSubscription(Request $request, StripeService $stripe): JsonResponse
    {
        $user = $request->user();

        if (!$user->stripe_subscription_id) {
            return response()->json(['error' => 'No tienes una suscripción activa.'], 400);
        }

        try {
            $subscription = $stripe->resumeSubscription($user->stripe_subscription_id);
            $this->syncUserFromSubscription($user, $subscription, $stripe);
        } catch (Throwable $e) {
            Log::error('Stripe resume subscription error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudo reactivar el plan: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tu plan se ha reactivado correctamente.',
        ]);
    }

    /**
     * Crea un SetupIntent para actualizar el método de pago dentro de la app.
     */
    public function setupIntent(Request $request, StripeService $stripe): JsonResponse
    {
        $user = $request->user();

        try {
            $intent = $stripe->createSetupIntent($user);
        } catch (Throwable $e) {
            Log::error('Stripe setup intent error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudo iniciar la actualización del método de pago.'], 500);
        }

        return response()->json(['clientSecret' => $intent->client_secret]);
    }

    /**
     * Establece el nuevo método de pago como predeterminado y sincroniza la tarjeta.
     */
    public function updatePaymentMethod(Request $request, StripeService $stripe): JsonResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'string'],
        ]);

        $user = $request->user();

        try {
            $pm = $stripe->setDefaultPaymentMethod($user, $validated['payment_method']);

            if ($pm->type === 'card' && !empty($pm->card)) {
                $user->forceFill([
                    'pm_type' => 'card',
                    'pm_last_four' => $pm->card->last4,
                    'pm_brand' => $pm->card->brand,
                ])->save();
            }
        } catch (Throwable $e) {
            Log::error('Stripe update payment method error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudo actualizar el método de pago: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Método de pago actualizado correctamente.',
            'brand' => ucfirst($user->pm_brand ?? ''),
            'last4' => $user->pm_last_four,
        ]);
    }

    /**
     * Cambia el plan de una suscripción existente (prorrateado).
     */
    public function changePlan(Request $request, StripeService $stripe): JsonResponse
    {
        $validated = $request->validate([
            'plan' => ['required', 'string', 'in:' . implode(',', self::PLANES_VALIDOS)],
            'interval' => ['nullable', 'string', 'in:' . implode(',', self::INTERVALOS_VALIDOS)],
        ]);

        $user = $request->user();

        if (!$user->stripe_subscription_id) {
            return response()->json(['error' => 'No tienes una suscripción activa para cambiar.'], 400);
        }

        $plan = $validated['plan'];
        $interval = $validated['interval'] ?? null;

        $isStorage = str_starts_with($plan, 'storage_');

        if (!$isStorage && !$interval) {
            return response()->json(['error' => 'Debes seleccionar un intervalo de facturación.'], 400);
        }

        try {
            $newPriceId = $stripe->priceId($plan, $interval);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        if (empty($newPriceId)) {
            return response()->json(['error' => 'Este plan/intervalo aún no tiene un precio configurado en Stripe.'], 400);
        }

        try {
            $subscription = $stripe->updateSubscriptionPlan(
                $user->stripe_subscription_id,
                $newPriceId,
                [
                    'user_id' => (string) $user->id,
                    'plan' => $plan,
                    'interval' => $interval ?? 'N/A',
                ],
            );

            // Sincronizar los nuevos datos
            $this->syncUserFromSubscription($user, $subscription, $stripe);

            // Garantizar que el plan quede guardado correctamente
            $user->forceFill(['stripe_plan' => $plan])->save();
        } catch (Throwable $e) {
            Log::error('Stripe change plan error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudo cambiar el plan: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Plan cambiado correctamente. El prorrateo se aplicará en tu próximo cobro.',
            'plan' => $plan,
            'planName' => ucfirst(str_replace('_', ' ', $plan)),
        ]);
    }

    /**
     * Crea una suscripción incompleta y devuelve el client_secret para
     * usar con Stripe Payment Element (formulario propio, estilo Netflix).
     */
    public function createSubscriptionElement(Request $request, StripeService $stripe): JsonResponse
    {
        $validated = $request->validate([
            'plan' => ['required', 'string', 'in:' . implode(',', self::PLANES_VALIDOS)],
            'interval' => ['nullable', 'string', 'in:' . implode(',', self::INTERVALOS_VALIDOS)],
        ]);

        $plan = $validated['plan'];
        $interval = $validated['interval'] ?? null;

        $isStorage = str_starts_with($plan, 'storage_');

        if (!$isStorage && !$interval) {
            return response()->json(['error' => 'Debes seleccionar un intervalo de facturación.'], 400);
        }

        try {
            $priceId = $stripe->priceId($plan, $interval);
        } catch (Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        if (empty($priceId)) {
            return response()->json(['error' => 'Este plan/intervalo aún no tiene un precio configurado en Stripe.'], 400);
        }

        try {
            $subscription = $stripe->createSubscriptionIncomplete(
                $request->user(),
                $priceId,
                [
                    'plan' => $plan,
                    'interval' => $interval ?? 'N/A',
                ],
            );
        } catch (Throwable $e) {
            Log::error('Stripe subscription element error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudo iniciar la suscripción: ' . $e->getMessage()], 500);
        }

        $clientSecret = $subscription->latest_invoice->confirmation_secret->client_secret ?? null;

        if (!$clientSecret) {
            return response()->json(['error' => 'No se pudo obtener el client_secret de la suscripción.'], 500);
        }

        return response()->json([
            'clientSecret' => $clientSecret,
            'subscriptionId' => $subscription->id,
        ]);
    }

    /**
     * Página de retorno tras un pago exitoso.
     */
    public function success(Request $request, StripeService $stripe): RedirectResponse
    {
        $sessionId = $request->query('session_id');
        $message = '¡Pago completado! Tu plan ya está activo.';

        if ($sessionId) {
            // Flujo de Checkout (con sesión)
            try {
                $session = $stripe->client()->checkout->sessions->retrieve($sessionId);
                $this->syncFromCheckoutSession($session, $stripe);
                if (($session->metadata->type ?? null) === 'member_addon') {
                    $message = '¡Pago completado! Ya tienes una cuenta adicional disponible.';
                }
            } catch (Throwable $e) {
                Log::warning('Stripe success sync error', ['message' => $e->getMessage()]);
            }
        } else {
            // Flujo de Payment Element: sincronizar desde la suscripción del usuario
            $user = $request->user();

            if ($user && $user->stripe_customer_id) {
                try {
                    $subscription = $stripe->latestSubscription($user->stripe_customer_id);
                    if ($subscription) {
                        $this->syncUserFromSubscription($user, $subscription, $stripe);
                    }
                } catch (Throwable $e) {
                    Log::warning('Stripe payment element sync error', ['message' => $e->getMessage()]);
                }
            }
        }

        return redirect()->route('configuracion')->with('success', $message);
    }

    /**
     * Sincroniza los datos de la suscripción de Stripe en el usuario.
     */
    private function syncUserFromSubscription(User $user, object $subscription, StripeService $stripe): void
    {
        // Determinar el plan desde metadata o desde el price ID
        $plan = $subscription->metadata->plan ?? null;
        if (!$plan && isset($subscription->items->data[0])) {
            $priceId = $subscription->items->data[0]->price->id;
            $plans = config('services.stripe.plans', []);
            foreach ($plans as $planKey => $intervals) {
                foreach ($intervals as $pid) {
                    if ($pid === $priceId) {
                        $plan = $planKey;
                        break 2;
                    }
                }
            }
        }

        // El periodo de renovación puede estar en la suscripción o en el item (API basil)
        $periodEnd = $subscription->current_period_end
            ?? ($subscription->items->data[0]->current_period_end ?? null);

        // Fecha de cancelación programada (cancel_at_period_end)
        $cancelAt = null;
        if (!empty($subscription->cancel_at_period_end)) {
            $cancelTs = $subscription->cancel_at ?? $periodEnd;
            $cancelAt = $cancelTs ? Carbon::createFromTimestamp($cancelTs) : null;
        }

        $data = [
            'stripe_subscription_id' => $subscription->id,
            'subscription_status' => $subscription->status,
            'subscription_renews_at' => $periodEnd ? Carbon::createFromTimestamp($periodEnd) : null,
            'subscription_cancel_at' => $cancelAt,
        ];

        if ($plan) {
            $data['stripe_plan'] = $plan;
        }

        if (!empty($subscription->default_payment_method)) {
            try {
                $pm = $stripe->retrievePaymentMethod($subscription->default_payment_method);
                if ($pm->type === 'card' && !empty($pm->card)) {
                    $data['pm_type'] = 'card';
                    $data['pm_last_four'] = $pm->card->last4;
                    $data['pm_brand'] = $pm->card->brand;
                }
            } catch (Throwable $e) {
                Log::warning('Could not retrieve payment method', ['error' => $e->getMessage()]);
            }
        }

        $user->forceFill($data)->save();
    }

    /**
     * Página de retorno cuando el usuario cancela el pago.
     */
    public function cancel(): RedirectResponse
    {
        return redirect()->route('configuracion')->with('info', 'El pago fue cancelado. No se realizó ningún cargo.');
    }

    /**
     * Maneja los eventos de webhook de Stripe (ruta pública, sin CSRF).
     */
    public function webhook(Request $request, StripeService $stripe): Response
    {
        try {
            $event = $stripe->constructWebhookEvent(
                $request->getContent(),
                $request->header('Stripe-Signature', ''),
            );
        } catch (Throwable $e) {
            Log::warning('Stripe webhook signature invalid', ['message' => $e->getMessage()]);

            return response('Invalid signature', 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $this->syncFromCheckoutSession($event->data->object, $stripe);
                break;

            case 'customer.subscription.updated':
            case 'customer.subscription.created':
            case 'customer.subscription.deleted':
                $this->syncFromSubscription($event->data->object);
                break;
        }

        return response('Webhook handled', 200);
    }

    /**
     * Sincroniza el plan del usuario a partir de una sesión de Checkout.
     */
    private function syncFromCheckoutSession(object $session, StripeService $stripe): void
    {
        $user = $this->resolveUser($session->customer ?? null, $session->metadata->user_id ?? null);

        if (! $user) {
            return;
        }

        $subscriptionId = $session->subscription ?? null;

        if (($session->metadata->type ?? null) === 'member_addon' && $subscriptionId) {
            try {
                $subscription = $stripe->retrieveSubscription($subscriptionId);
                $this->syncMemberAddon($user, $subscription);
            } catch (Throwable $e) {
                Log::warning('Could not sync member addon checkout', ['error' => $e->getMessage()]);
            }

            return;
        }

        $data = [
            'stripe_customer_id' => $session->customer ?? $user->stripe_customer_id,
            'stripe_plan' => $session->metadata->plan ?? $user->stripe_plan,
        ];

        if ($subscriptionId) {
            $data['stripe_subscription_id'] = $subscriptionId;

            try {
                $subscription = $stripe->retrieveSubscription($subscriptionId);
                $data['subscription_status'] = $subscription->status;
                $data['subscription_renews_at'] = $subscription->current_period_end
                    ? Carbon::createFromTimestamp($subscription->current_period_end)
                    : null;

                // Obtener información del método de pago
                if (!empty($subscription->default_payment_method)) {
                    try {
                        $paymentMethod = $stripe->client()->paymentMethods->retrieve($subscription->default_payment_method);
                        if ($paymentMethod->type === 'card' && !empty($paymentMethod->card)) {
                            $data['pm_type'] = 'card';
                            $data['pm_last_four'] = $paymentMethod->card->last4;
                            $data['pm_brand'] = $paymentMethod->card->brand; // visa, mastercard, amex, etc.
                        }
                    } catch (Throwable $e) {
                        Log::warning('Could not retrieve payment method', ['error' => $e->getMessage()]);
                    }
                }
            } catch (Throwable $e) {
                $data['subscription_status'] = 'active';
            }
        }

        $user->forceFill($data)->save();
    }

    /**
     * Sincroniza el plan del usuario a partir de un objeto Subscription.
     */
    private function syncFromSubscription(object $subscription): void
    {
        $user = $this->resolveUser(
            $subscription->customer ?? null,
            $subscription->metadata->user_id ?? null,
        );

        if (! $user) {
            return;
        }

        if (($subscription->metadata->type ?? null) === 'member_addon') {
            $this->syncMemberAddon($user, $subscription);

            return;
        }

        $user->forceFill([
            'stripe_subscription_id' => $subscription->id,
            'subscription_status' => $subscription->status,
            'subscription_renews_at' => ! empty($subscription->current_period_end)
                ? Carbon::createFromTimestamp($subscription->current_period_end)
                : null,
        ])->save();
    }

    private function syncMemberAddon(User $user, object $subscription): void
    {
        $user->memberAddons()->updateOrCreate(
            ['stripe_subscription_id' => $subscription->id],
            [
                'quantity' => max(1, (int) ($subscription->metadata->quantity ?? 1)),
                'status' => (string) ($subscription->status ?? 'active'),
            ],
        );
    }

    /**
     * Localiza al usuario por su Customer de Stripe o por el metadata user_id.
     */
    private function resolveUser(?string $customerId, ?string $userId): ?User
    {
        if ($customerId) {
            $user = User::where('stripe_customer_id', $customerId)->first();
            if ($user) {
                return $user;
            }
        }

        if ($userId) {
            return User::find($userId);
        }

        return null;
    }
}
