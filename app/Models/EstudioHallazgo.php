<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstudioHallazgo extends Model
{
    protected $table = 'estudio_hallazgos';

    protected $fillable = [
        'estudio_id',
        'hallazgo_id',
        'detectado_por',
    ];

    public function estudio(): BelongsTo
    {
        return $this->belongsTo(Estudio::class);
    }

    public function hallazgo(): BelongsTo
    {
        return $this->belongsTo(Hallazgo::class);
    }
}
