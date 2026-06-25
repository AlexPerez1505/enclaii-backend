<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = [
<<<<<<< HEAD
        'folio', 'nombre_completo', 'identificacion', 'fecha_nacimiento',
        'edad', 'peso', 'altura', 'sexo', 'direccion', 'telefono', 'email',
        'medico', 'procedimiento', 'anestesiologo', 'referido_por',
        'equipo_utilizado', 'diagnostico_preliminar', 'foto',
=======
        'folio',
        'nombre_completo',
        'identificacion',
        'fecha_nacimiento',
        'edad',
        'peso',
        'altura',
        'sexo',
        'direccion',
        'telefono',
        'email',
        'medico',
        'procedimiento',
        'anestesiologo',
        'referido_por',
        'equipo_utilizado',
        'diagnostico_preliminar',
        'foto',
>>>>>>> origin/main
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
<<<<<<< HEAD
    ];
}
=======
        'edad' => 'integer',
        'peso' => 'decimal:2',
        'altura' => 'decimal:2',
    ];
}
>>>>>>> origin/main
