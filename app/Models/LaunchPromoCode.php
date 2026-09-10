<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LaunchPromoCode extends Model
{
    public const TYPE_LAUNCH = 'launch';
    public const TYPE_TEST = 'test';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_RESERVED = 'reserved';
    public const STATUS_REDEEMED = 'redeemed';
    public const STATUS_REVOKED = 'revoked';

    protected $fillable = [
        'code',
        'token',
        'token_hash',
        'type',
        'plan',
        'interval',
        'trial_months',
        'stripe_coupon_id',
        'stripe_promotion_code_id',
        'status',
        'reserved_by',
        'reserved_at',
        'redeemed_by',
        'redeemed_at',
        'checkout_session_id',
        'stripe_subscription_id',
        'expires_at',
        'revoked_at',
    ];

    protected function casts(): array
    {
        return [
            'token' => 'encrypted',
            'reserved_at' => 'datetime',
            'redeemed_at' => 'datetime',
            'expires_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public static function findByToken(string $token): ?self
    {
        return static::query()
            ->where('token_hash', hash('sha256', $token))
            ->first();
    }

    public static function normalizeCode(?string $code): string
    {
        return preg_replace('/\s+/', '', Str::upper(trim($code ?? ''))) ?? '';
    }

    public static function findByCode(string $code): ?self
    {
        return static::query()
            ->where('code', static::normalizeCode($code))
            ->first();
    }

    public function reservedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reserved_by');
    }

    public function redeemedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'redeemed_by');
    }

    public function isAvailable(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        if ($this->revoked_at || $this->reserved_by || $this->redeemed_by) {
            return false;
        }

        return ! $this->expires_at || $this->expires_at->isFuture();
    }

    public function isOwnedBy(User $user): bool
    {
        return (int) $this->reserved_by === (int) $user->id
            || (int) $this->redeemed_by === (int) $user->id;
    }

    public function hasStripePromotionCode(): bool
    {
        return filled($this->stripe_promotion_code_id);
    }

    public function reserveFor(User $user): void
    {
        $this->forceFill([
            'status' => self::STATUS_RESERVED,
            'reserved_by' => $user->id,
            'reserved_at' => now(),
        ])->save();
    }

    public function markRedeemedFor(User $user, ?string $subscriptionId = null): void
    {
        $this->forceFill([
            'status' => self::STATUS_REDEEMED,
            'redeemed_by' => $user->id,
            'redeemed_at' => $this->redeemed_at ?: now(),
            'stripe_subscription_id' => $subscriptionId ?: $this->stripe_subscription_id,
        ])->save();
    }
}
