<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Sala;
class Cita extends Model
{
    use BelongsToClinica;

    protected $table = 'citas';

    protected $fillable = [
        'clinica_id',
        'paciente_id',
        'paciente_nombre',
        'procedimiento',
        'fecha',
        'hora',
        'duracion_minutos',
        'estado',
        'sala_id',
        'notas',
    ];

    protected $casts = [
        'fecha' => 'date',
        'duracion_minutos' => 'integer',
    ];

    public function salaRelacion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Sala::class, 'sala_id'); // Asegúrate que el FK es sala_id
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function prediccionPreEstudio(): HasOne
    {
        return $this->hasOne(PrediccionPreEstudio::class);
    }

    public function estudios(): HasMany
    {
        return $this->hasMany(Estudio::class);
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
