<?php

namespace App\Console\Commands;

use App\Models\LaunchPromoCode;
use App\Services\StripeService;
use Illuminate\Console\Command;
use Throwable;

class CreateStripePromotionCodes extends Command
{
    protected $signature = 'promo:create-stripe-promotion-codes
        {--coupon= : ID de un coupon existente en Stripe}
        {--type= : Filtra por tipo local: launch o test}
        {--limit= : Numero maximo de codigos locales a enlazar}';

    protected $description = 'Crea o enlaza Promotion Codes de Stripe para los codigos promocionales locales.';

    public function handle(StripeService $stripe): int
    {
        $type = $this->option('type');

        if ($type && ! in_array($type, [LaunchPromoCode::TYPE_LAUNCH, LaunchPromoCode::TYPE_TEST], true)) {
            $this->error('El tipo debe ser launch o test.');

            return self::FAILURE;
        }

        $query = LaunchPromoCode::query()
            ->where('status', LaunchPromoCode::STATUS_ACTIVE)
            ->whereNull('stripe_promotion_code_id')
            ->orderBy('type')
            ->orderBy('code');

        if ($type) {
            $query->where('type', $type);
        }

        $limit = (int) ($this->option('limit') ?: 0);
        if ($limit > 0) {
            $query->limit($limit);
        }

        $codes = $query->get();

        if ($codes->isEmpty()) {
            $this->info('No hay codigos activos pendientes de enlazar con Stripe.');

            return self::SUCCESS;
        }

        $couponId = $this->option('coupon');
        $durationMonths = (int) ($codes->max('trial_months') ?: 6);

        if (! $couponId) {
            $coupon = $stripe->createRepeatingCoupon(
                'ENCLAII '.$durationMonths.' meses gratis',
                $durationMonths,
                metadata: [
                    'type' => 'launch_promo',
                    'duration_months' => (string) $durationMonths,
                ],
            );
            $couponId = $coupon->id;
            $this->info('Coupon creado en Stripe: '.$couponId);
        }

        $rows = [];
        $errors = 0;

        foreach ($codes as $promoCode) {
            try {
                $promotionCode = $stripe->findActivePromotionCode($promoCode->code)
                    ?: $stripe->createPromotionCode(
                        $couponId,
                        $promoCode->code,
                        [
                            'local_promo_code_id' => (string) $promoCode->id,
                            'type' => $promoCode->type,
                            'plan' => $promoCode->plan,
                            'interval' => $promoCode->interval,
                            'discount_months' => (string) $promoCode->trial_months,
                        ],
                    );

                $linkedCouponId = $this->couponIdFromPromotionCode($promotionCode) ?: $couponId;

                $promoCode->forceFill([
                    'stripe_coupon_id' => $linkedCouponId,
                    'stripe_promotion_code_id' => $promotionCode->id,
                ])->save();

                $rows[] = [
                    $promoCode->code,
                    $promoCode->type,
                    $linkedCouponId,
                    $promotionCode->id,
                ];
            } catch (Throwable $e) {
                $errors++;
                $this->error($promoCode->code.': '.$e->getMessage());
            }
        }

        if ($rows) {
            $this->table(['Codigo', 'Tipo', 'Coupon', 'Promotion Code'], $rows);
        }

        $this->info('Enlazados: '.count($rows).'. Errores: '.$errors.'.');

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function couponIdFromPromotionCode(object $promotionCode): ?string
    {
        $promotion = $promotionCode->promotion ?? null;
        $promotionCoupon = is_array($promotion)
            ? ($promotion['coupon'] ?? null)
            : ($promotion->coupon ?? null);

        if (is_string($promotionCoupon)) {
            return $promotionCoupon;
        }

        $coupon = $promotionCode->coupon ?? null;

        if (is_string($coupon)) {
            return $coupon;
        }

        return $coupon->id ?? null;
    }
}
