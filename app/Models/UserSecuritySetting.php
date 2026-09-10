<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSecuritySetting extends Model
{
    protected $fillable = [
        'user_id',
        'require_password_for_studies',
        'require_password_for_patients',
        'audit_sensitive_actions',
        'two_factor_email_enabled',
        'two_factor_email_confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'require_password_for_studies' => 'boolean',
            'require_password_for_patients' => 'boolean',
            'audit_sensitive_actions' => 'boolean',
            'two_factor_email_enabled' => 'boolean',
            'two_factor_email_confirmed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
