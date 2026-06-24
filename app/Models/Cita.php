<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cita extends Model
{
    protected $table = 'citas';

    protected $fillable = [
        'paciente_id',
        'paciente_nombre',
        'procedimiento',
        'fecha',
        'hora',
        'duracion_minutos',
        'estado',
        'sala',
        'notas',
    ];

    protected $casts = [
        'fecha' => 'date',
        'duracion_minutos' => 'integer',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function prediccionPreEstudio(): HasOne
    {
        return $this->hasOne(PrediccionPreEstudio::class);
    }

    public function getEstadoClaseAttribute(): string
    {
        return match ($this->estado) {
            'completado' => 'ev-done',
            'en_espera' => 'ev-wait',
            'cancelado' => 'ev-cancel',
            default => 'ev-soon',
        };
    }

    public function getEstadoTextoAttribute(): string
    {
        return match ($this->estado) {
            'completado' => 'Completado',
            'en_espera' => 'En espera',
            'cancelado' => 'Cancelado',
            default => 'Próximo',
        };
    }
}
