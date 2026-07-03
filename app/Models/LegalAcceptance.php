<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegalAcceptance extends Model
{
    protected $fillable = [
        'user_id',
        'documento',
        'version',
        'fecha',
        'hora',
        'ip',
        'navegador',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
