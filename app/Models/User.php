<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    use Notifiable;

    protected static function booted(): void
    {
        static::creating(function (User $user): void {
            if (! $user->clinica_id && Schema::hasTable('clinicas')) {
                $hasPlan = in_array($user->subscription_status, ['active', 'trialing'], true);
                $clinica = $hasPlan
                    ? Clinica::create([
                        'nombre' => 'Clínica de '.$user->name,
                        'is_shared' => false,
                    ])
                    : Clinica::shared();

                $user->clinica_id = $clinica->id;
                $user->clinica_rol = $hasPlan ? 'propietario' : 'usuario';
            }
        });

        static::saved(function (User $user): void {
            $user->ensurePrivateClinicForPlan();
        });
    }

    protected $fillable = [
        'clinica_id',
        'clinica_rol',
        'name',
        'email',
        'password',
        'password_changed_at',
        'phone',
        'specialty',
        'professional_license',
        'medical_area',
        'position',
        'profile_completed',
        'settings',
        'signature_path',
        'signature_updated_at',
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
            'password_changed_at' => 'datetime',
            'profile_completed' => 'boolean',
            'settings' => 'array',
            'signature_updated_at' => 'datetime',
            'subscription_renews_at' => 'datetime',
            'subscription_cancel_at' => 'datetime',
        ];
    }

    /**
     * Indica si el usuario tiene una suscripción activa en Stripe.
     */
    public function subscribed(): bool
    {
        return in_array($this->billingUser()->subscription_status, ['active', 'trialing'], true);
    }

    public function billingUser(): self
    {
        if (in_array($this->subscription_status, ['active', 'trialing'], true)) {
            return $this;
        }

        if (! $this->clinica_id) {
            return $this;
        }

        return static::query()
            ->where('clinica_id', $this->clinica_id)
            ->where('clinica_rol', 'propietario')
            ->whereIn('subscription_status', ['active', 'trialing'])
            ->orderBy('id')
            ->first() ?? $this;
    }

    public function clinicMemberLimit(): int
    {
        $billingUser = $this->billingUser();

        return $billingUser->baseClinicMemberLimit()
            + $billingUser->memberAddons()
                ->whereIn('status', ['active', 'trialing'])
                ->sum('quantity');
    }

    public function baseClinicMemberLimit(): int
    {
        return match ($this->billingUser()->stripe_plan) {
            'red_medica' => 50,
            'hospital' => 15,
            'clinica' => 5,
            default => 1,
        };
    }

    public function clinicMemberUpgradeOffer(): array
    {
        return match ($this->billingUser()->stripe_plan) {
            'clinica' => [
                'type' => 'plan_upgrade',
                'target_plan' => 'hospital',
                'target_label' => 'Hospital',
                'new_limit' => 15,
            ],
            'hospital' => [
                'type' => 'plan_upgrade',
                'target_plan' => 'red_medica',
                'target_label' => 'Red Médica',
                'new_limit' => 50,
            ],
            'red_medica' => [
                'type' => 'member_addon',
                'price_mxn' => 5000,
                'additional_slots' => 1,
            ],
            default => [
                'type' => 'plan_upgrade',
                'target_plan' => 'clinica',
                'target_label' => 'Clínica',
                'new_limit' => 5,
            ],
        };
    }

    public function memberAddons(): HasMany
    {
        return $this->hasMany(ClinicMemberAddon::class);
    }

    public function ensurePrivateClinicForPlan(): void
    {
        if (! in_array($this->subscription_status, ['active', 'trialing'], true)) {
            return;
        }

        $clinic = $this->clinica()->first();
        if (! $clinic?->is_shared) {
            return;
        }

        $privateClinic = Clinica::create([
            'nombre' => 'Clínica de '.$this->name,
            'is_shared' => false,
        ]);

        $this->forceFill([
            'clinica_id' => $privateClinic->id,
            'clinica_rol' => 'propietario',
        ])->saveQuietly();

        $this->setRelation('clinica', $privateClinic);
    }

    /**
     * Indica si la suscripción está programada para cancelarse al final del ciclo.
     */
    public function cancelScheduled(): bool
    {
        return $this->subscription_cancel_at !== null;
    }

    public function configurationBackups(): HasMany
    {
        return $this->hasMany(ConfigurationBackup::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function connectedSessions(): HasMany
    {
        return $this->hasMany(UserSession::class);
    }

    public function securitySetting(): HasOne
    {
        return $this->hasOne(UserSecuritySetting::class);
    }

    public function securityPreferences(): array
    {
        $settings = $this->relationLoaded('securitySetting')
            ? $this->getRelation('securitySetting')
            : $this->securitySetting()->first();

        return [
            'require_password_for_studies' => $settings?->require_password_for_studies ?? true,
            'require_password_for_patients' => $settings?->require_password_for_patients ?? true,
            'audit_sensitive_actions' => $settings?->audit_sensitive_actions ?? true,
        ];
    }

    public function criticalPasswordRequired(string $scope): bool
    {
        $preferences = $this->securityPreferences();

        return match ($scope) {
            'studies' => $preferences['require_password_for_studies'],
            'patients' => $preferences['require_password_for_patients'],
            default => false,
        };
    }

    public function auditSensitiveActionsEnabled(): bool
    {
        return $this->securityPreferences()['audit_sensitive_actions'];
    }

    public function clinica(): BelongsTo
    {
        return $this->belongsTo(Clinica::class);
    }

    public function pacientes(): HasMany
    {
        return $this->hasMany(Paciente::class, 'clinica_id', 'clinica_id');
    }

    /**
     * Valores por defecto de la configuración general.
     */
    public static function defaultSettings(): array
    {
        return [
            'timezone' => 'America/Mexico_City',
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
            'qr_default_expiration_hours' => '48',
            'qr_default_patient_message' => 'Por favor completa tus datos con la mayor información posible. Gracias.',
            'qr_whatsapp_template' => 'Hola, te comparto tu enlace de pre-registro de ENCLAII: {enlace}',
            'qr_patient_photo_enabled' => true,
            'qr_patient_photo_required' => false,
            'qr_allow_camera_photo' => true,
            'qr_allow_gallery_photo' => true,
            'qr_required_fields' => [],
            'qr_consent_text' => 'Autorizo el envío de estos datos y, si la adjunto, mi fotografía a {clinica} para preparar mi atención y crear mi expediente después de que el personal médico revise la información.',
            'qr_duplicate_check' => true,
            'qr_duplicate_action' => 'warn',
            'capture_auto_capture' => true,
            'capture_auto_save' => true,
            'capture_auto_interval' => 30,
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
