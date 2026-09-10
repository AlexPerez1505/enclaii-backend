<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudReporteIa extends Model
{
    use BelongsToClinica;

    protected $table = 'solicitudes_reporte_ia';

    public const ESTADO_PENDIENTE = 'pendiente';
    public const ESTADO_PROCESANDO = 'procesando';
    public const ESTADO_LISTO = 'listo';
    public const ESTADO_ERROR = 'error';

    protected $fillable = [
        'clinica_id',
        'estudio_id',
        'usuario_id',
        'estado',
        'resultado',
        'reporte_id',
        'error_mensaje',
    ];

    protected $casts = [
        'resultado' => 'array',
    ];

    public function estudio(): BelongsTo
    {
        return $this->belongsTo(Estudio::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function reporte(): BelongsTo
    {
        return $this->belongsTo(Reporte::class);
    }

    public function terminada(): bool
    {
        return in_array($this->estado, [self::ESTADO_LISTO, self::ESTADO_ERROR], true);
    }
}
