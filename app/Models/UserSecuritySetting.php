<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSecuritySetting extends Model
{
    protected $fillable = [
        'require_password_for_studies',
        'require_password_for_patients',
        'audit_sensitive_actions',
    ];

    protected function casts(): array
    {
        return [
            'require_password_for_studies' => 'boolean',
            'require_password_for_patients' => 'boolean',
            'audit_sensitive_actions' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
