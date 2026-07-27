<?php

namespace App\Services;

use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TwoFactorEmailService
{
    private const CODE_LENGTH = 6;
    private const EXPIRATION_MINUTES = 5;
    private const MAX_ATTEMPTS = 3;

    public function generateAndSend(User $user): string
    {
        $code = $this->generateCode();
        $this->storeCode($user, $code);

        Mail::to($user->email)->send(new TwoFactorCodeMail($code, $user));

        return $code;
    }

    public function verify(User $user, string $code): bool
    {
        $key = $this->codeKey($user);
        $stored = Cache::get($key);

        if (! $stored || ! is_array($stored)) {
            return false;
        }

        if ($stored['attempts'] >= self::MAX_ATTEMPTS) {
            return false;
        }

        $stored['attempts']++;
        Cache::put($key, $stored, now()->addMinutes(self::EXPIRATION_MINUTES));

        if (! hash_equals((string) $stored['code'], $code)) {
            return false;
        }

        Cache::forget($key);
        return true;
    }

    public function markConfirmed(User $user): void
    {
        $user->securitySetting()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'two_factor_email_enabled' => true,
                'two_factor_email_confirmed_at' => now(),
            ]
        );
    }

    public function disable(User $user): void
    {
        $user->securitySetting()->updateOrCreate(
            ['user_id' => $user->id],
            ['two_factor_email_enabled' => false]
        );
        Cache::forget($this->codeKey($user));
    }

    public function isEnabled(User $user): bool
    {
        return (bool) ($user->securitySetting?->two_factor_email_enabled ?? false);
    }

    private function storeCode(User $user, string $code): void
    {
        Cache::put(
            $this->codeKey($user),
            [
                'code' => $code,
                'attempts' => 0,
                'sent_at' => now()->timestamp,
            ],
            now()->addMinutes(self::EXPIRATION_MINUTES)
        );
    }

    private function codeKey(User $user): string
    {
        return '2fa_email:'.$user->id;
    }

    private function generateCode(): string
    {
        return str_pad((string) random_int(0, 999999), self::CODE_LENGTH, '0', STR_PAD_LEFT);
    }
}