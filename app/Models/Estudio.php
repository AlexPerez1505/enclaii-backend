<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estudio extends Model
{
    use BelongsToClinica;

    protected $table = 'estudios';

    protected $fillable = [
        'clinica_id',
        'paciente_id',
        'cita_id',
        'paciente_nombre',
        'folio',
        'tipo',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'duracion_segundos',
        'estado',
        'medico',
        'sala',
        'equipo',
        'diagnostico',
        'descripcion',
        'observaciones',
        'configuracion_video',
        'configuracion_audio',
        'configuracion_texto',
        'video_path',
        'reporte_path',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
        'duracion_segundos' => 'integer',
        'configuracion_video' => 'array',
        'configuracion_audio' => 'array',
        'configuracion_texto' => 'array',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function cita(): BelongsTo
    {
        return $this->belongsTo(Cita::class);
    }

    public function archivos(): HasMany
    {
        return $this->hasMany(EstudioArchivo::class);
    }

    public function capturas(): HasMany
    {
        return $this->hasMany(EstudioArchivo::class)->where('tipo', 'imagen');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(EstudioArchivo::class)->where('tipo', 'video');
    }

    public function estudioHallazgos(): HasMany
    {
        return $this->hasMany(EstudioHallazgo::class);
    }

    public function hallazgos(): BelongsToMany
    {
        return $this->belongsToMany(Hallazgo::class, 'estudio_hallazgos')
            ->withPivot('detectado_por')
            ->withTimestamps();
    }

    public function reportes(): HasMany
    {
        return $this->hasMany(Reporte::class);
    }

    public function getEstadoTextoAttribute(): string
    {
        return match ($this->estado) {
            'en_proceso' => 'En proceso',
            'cancelado' => 'Cancelado',
            'archivado' => 'Archivado',
            default => 'Completado',
        };
    }
}
