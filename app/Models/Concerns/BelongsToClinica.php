<?php

namespace App\Models\Concerns;

use App\Models\Clinica;
use App\Models\Scopes\ClinicaScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToClinica
{
    public static function bootBelongsToClinica(): void
    {
        static::addGlobalScope(new ClinicaScope);

        static::creating(function (Model $model): void {
            $clinicaId = Auth::user()?->clinica_id
                ?? $model->getAttribute('clinica_id');

            if (! $clinicaId) {
                $privateClinics = Clinica::query()->where('is_shared', false);
                $clinicaId = $privateClinics->count() === 1
                    ? $privateClinics->value('id')
                    : (Clinica::query()->count() === 1 ? Clinica::query()->value('id') : null);
            }

            if ($clinicaId) {
                $model->setAttribute('clinica_id', $clinicaId);
            }
        });

        static::updating(function (Model $model): void {
            if ($clinicaId = Auth::user()?->clinica_id) {
                $model->setAttribute('clinica_id', $clinicaId);
            }
        });
    }

    public function clinica(): BelongsTo
    {
        return $this->belongsTo(Clinica::class);
    }
}
