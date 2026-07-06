<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasRoles;
    use Notifiable;

    protected $fillable = [
        'name',
        'foto_perfil',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'sexo',
        'email',
        'password',
        'phone',
        'specialty',
        'subespecialidad',
        'universidad',
        'professional_license',
        'medical_area',
        'position',
        'clinica_nombre',
        'clinica_ciudad',
        'clinica_direccion',
        'clinica_codigo_postal',
        'clinica_telefono',
        'clinica_estado',
        'rfc',
        'razon_social',
        'regimen_fiscal',
        'correo_facturacion',
        'constancia_fiscal',
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
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

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
            'notif_new_studies_email' => true,
            'notif_new_studies_screen' => true,
            'notif_reminders_email' => false,
            'notif_reminders_screen' => true,
            'notif_updates_email' => false,
            'notif_updates_screen' => true,
            'notif_maintenance_email' => false,
            'notif_maintenance_screen' => true,
            'notif_privacy_email' => false,
            'notif_privacy_screen' => true,
            'notif_messages_screen' => true,
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