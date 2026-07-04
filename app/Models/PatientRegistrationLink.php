<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PatientRegistrationLink extends Model
{
    use BelongsToClinica;

    protected $fillable = [
        'clinica_id',
        'created_by',
        'token',
        'token_hash',
        'status',
        'expires_at',
        'submitted_at',
        'revoked_at',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'token' => 'encrypted',
            'expires_at' => 'datetime',
            'submitted_at' => 'datetime',
            'revoked_at' => 'datetime',
            'archived_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function preregistration(): HasOne
    {
        return $this->hasOne(PatientPreregistration::class, 'registration_link_id');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'active'
            && ! $this->revoked_at
            && ! $this->archived_at
            && $this->expires_at->isFuture()
            && ! $this->preregistration()->exists();
    }
}
