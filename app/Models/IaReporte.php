<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IaReporte extends Model
{
    protected $table = 'ia_reportes';

    protected $fillable = [
        'reporte_id',
        'analisis_ia',
        'version_modelo',
    ];

    protected $casts = [
        'analisis_ia' => 'array',
    ];

    public function reporte(): BelongsTo
    {
        return $this->belongsTo(Reporte::class);
    }
}
