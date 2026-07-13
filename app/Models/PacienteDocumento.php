<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PacienteDocumento extends Model
{
    protected $table = 'paciente_documentos';

    protected $fillable = [
        'paciente_id',
        'nombre_original',
        'path',
        'mime_type',
        'size_bytes',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }
}
