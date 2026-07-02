<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfigurationBackup extends Model
{
    protected $fillable = [
        'user_id',
        'uuid',
        'name',
        'type',
        'version',
        'scope',
        'payload',
        'status',
        'size',
        'restored_at',
    ];

    protected function casts(): array
    {
        return [
            'scope' => 'array',
            'payload' => 'encrypted:array',
            'restored_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
