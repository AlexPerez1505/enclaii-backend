<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = [
        'folio', 'nombre_completo', 'identificacion', 'fecha_nacimiento',
        'edad', 'peso', 'altura', 'sexo', 'direccion', 'telefono', 'email',
        'medico', 'procedimiento', 'anestesiologo', 'referido_por',
        'equipo_utilizado', 'diagnostico_preliminar', 'foto',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];
}
