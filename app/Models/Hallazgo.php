<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hallazgo extends Model
{
    use BelongsToClinica;

    protected $table = 'hallazgos';

    protected $fillable = [
        'clinica_id',
        'nombre',
        'codigo_cie',
        'es_critico',
    ];

    protected $casts = [
        'es_critico' => 'boolean',
    ];

    public function estudioHallazgos(): HasMany
    {
        return $this->hasMany(EstudioHallazgo::class);
    }

    public function estudios(): BelongsToMany
    {
        return $this->belongsToMany(Estudio::class, 'estudio_hallazgos')
            ->withPivot('detectado_por')
            ->withTimestamps();
    }
}
