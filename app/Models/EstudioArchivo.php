<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstudioArchivo extends Model
{
    use BelongsToClinica;

    protected $table = 'estudio_archivos';

    protected $fillable = [
        'clinica_id',
        'estudio_id',
        'paciente_id',
        'tipo',
        'categoria',
        'nombre_original',
        'nombre',
        'path',
        'mime_type',
        'size_bytes',
        'descripcion',
        'capturado_en',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'capturado_en' => 'datetime',
    ];

    public function estudio(): BelongsTo
    {
        return $this->belongsTo(Estudio::class);
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }
}
