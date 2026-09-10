<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;

class Anestesiologo extends Model
{
    use BelongsToClinica;

    protected $fillable = [
        'clinica_id',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'especialidad',
        'cedula_profesional',
        'correo',
        'telefono',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    protected $appends = ['nombre_completo'];

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}");
    }
}
