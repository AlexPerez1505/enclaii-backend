<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IaReporte extends Model
{
    use BelongsToClinica;

    protected $table = 'ia_reportes';

    protected $fillable = [
        'clinica_id',
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

    public function plantilla(): BelongsTo
    {
        return $this->reporte->plantilla();
    }
}
