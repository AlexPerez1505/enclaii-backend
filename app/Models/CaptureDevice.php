<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class CaptureDevice extends Model
{
    use HasApiTokens;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'name',
        'device_uid',
        'last_seen_at',
        'last_ip',
        'is_active',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}