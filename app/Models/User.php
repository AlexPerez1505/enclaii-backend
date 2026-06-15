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
        ];
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