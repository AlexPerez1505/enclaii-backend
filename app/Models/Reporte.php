<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reporte extends Model
{
    protected $table = 'reportes';

    protected $fillable = [
        'estudio_id',
        'usuario_id',
        'plantilla_id',
        'contenido_texto',
        'contenido_html',
        'contiene_hallazgos_criticos',
    ];

    protected $casts = [
        'contiene_hallazgos_criticos' => 'boolean',
    ];

    public function estudio(): BelongsTo
    {
        return $this->belongsTo(Estudio::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function plantilla(): BelongsTo
    {
        return $this->belongsTo(Plantilla::class);
    }

    public function iaReportes(): HasMany
    {
        return $this->hasMany(IaReporte::class);
    }
}
