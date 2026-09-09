<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class Plantilla extends Model
{
    protected $table = 'plantillas';

    protected $fillable = [
        'clinica_id',
        'clave',
        'nombre',
        'descripcion',
        'tipo_plantilla',
        'tipo_estudio',
        'titulo',
        'subtitulo',
        'secciones',
        'columnas',
        'num_imagenes',
        'configuracion',
        'solo_imagenes',
        'es_predeterminada',
        'orden',
    ];

    protected $casts = [
        'secciones' => 'array',
        'configuracion' => 'array',
        'columnas' => 'integer',
        'num_imagenes' => 'integer',
        'solo_imagenes' => 'boolean',
        'es_predeterminada' => 'boolean',
        'orden' => 'integer',
    ];

    public function scopeInforme($query)
    {
        return $query->where('tipo_plantilla', 'informe');
    }

    public function scopeImagenes($query)
    {
        return $query->where('tipo_plantilla', 'imagenes');
    }

    public function clinica(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Clinica::class);
    }

    /**
     * Todas las plantillas visibles para una clínica: la plantilla global
     * (clinica_id null) de cada clave, sustituida por la copia personalizada
     * de la clínica cuando existe. Nunca se editan ni se ven las plantillas
     * de otras clínicas.
     */
    public static function visibleForCurrentClinica(?int $clinicaId = null): Collection
    {
        $clinicaId ??= Auth::user()?->clinica_id;

        return static::query()
            ->where(function ($query) use ($clinicaId) {
                $query->whereNull('clinica_id');
                if ($clinicaId) {
                    $query->orWhere('clinica_id', $clinicaId);
                }
            })
            ->get()
            ->groupBy('clave')
            ->map(fn (Collection $grupo) => $grupo->firstWhere('clinica_id', $clinicaId) ?? $grupo->first());
    }

    /**
     * Resuelve la plantilla aplicable para una clave: la copia de la clínica
     * si existe, o la plantilla global por defecto.
     */
    public static function forClave(string $clave, ?int $clinicaId = null): ?self
    {
        $clinicaId ??= Auth::user()?->clinica_id;

        $propia = $clinicaId
            ? static::query()->where('clave', $clave)->where('clinica_id', $clinicaId)->first()
            : null;

        return $propia ?? static::query()->where('clave', $clave)->whereNull('clinica_id')->first();
    }

    public static function idForClave(string $clave, ?int $clinicaId = null): ?int
    {
        return static::forClave($clave, $clinicaId)?->id;
    }
}
