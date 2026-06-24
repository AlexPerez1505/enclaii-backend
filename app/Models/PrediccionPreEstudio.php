<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrediccionPreEstudio extends Model
{
    protected $table = 'predicciones_pre_estudio';

    protected $fillable = [
        'cita_id',
        'posibles_hallazgos',
        'recomendacion_clinica',
        'plantilla_sugerida',
    ];

    protected $casts = [
        'posibles_hallazgos' => 'array',
    ];

    public function cita(): BelongsTo
    {
        return $this->belongsTo(Cita::class);
    }
}
