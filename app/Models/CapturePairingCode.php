<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapturePairingCode extends Model
{
    protected $fillable = [
        'tenant_id',
        'user_id',
        'study_id',
        'code_hash',
        'expires_at',
        'used_at',
        'device_name',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }

    public function isUsed(): bool
    {
        return ! is_null($this->used_at);
    }
}