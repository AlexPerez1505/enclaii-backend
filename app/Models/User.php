<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'specialty',
        'professional_license',
        'medical_area',
        'position',
        'profile_completed',
        'settings',
        'stripe_customer_id',
        'stripe_subscription_id',
        'stripe_plan',
        'subscription_status',
        'subscription_renews_at',
        'subscription_cancel_at',
        'pm_type',
        'pm_last_four',
        'pm_brand',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'profile_completed' => 'boolean',
            'settings' => 'array',
            'subscription_renews_at' => 'datetime',
            'subscription_cancel_at' => 'datetime',
        ];
    }

    /**
     * Indica si el usuario tiene una suscripción activa en Stripe.
     */
    public function subscribed(): bool
    {
        return in_array($this->subscription_status, ['active', 'trialing'], true);
    }

    /**
     * Indica si la suscripción está programada para cancelarse al final del ciclo.
     */
    public function cancelScheduled(): bool
    {
        return $this->subscription_cancel_at !== null;
    }

    /**
     * Valores por defecto de la configuración general.
     */
    public static function defaultSettings(): array
    {
        return [
            'timezone' => '(GMT-06:00) Ciudad de México',
            'date_format' => 'DD/MM/YYYY',
            'time_format' => '12 horas (AM/PM)',
            'autosave' => true,
            'confirm_delete' => true,
            'default_view' => 'Dashboard',
            'items_per_page' => '25',
            'animations' => true,
            'compact' => false,
            'reading_mode' => false,
            'notif_email' => true,
            'notif_push' => true,
            'notif_new_studies' => true,
            'notif_reports' => true,
            'notif_reminders' => false,
        ];
    }

    /**
     * Configuración del usuario combinada con los valores por defecto.
     */
    public function resolvedSettings(): array
    {
        return array_merge(static::defaultSettings(), $this->settings ?? []);
    }
}