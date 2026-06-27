<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    protected $fillable = [
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
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'edad' => 'integer',
        'peso' => 'decimal:2',
        'altura' => 'decimal:2',
    ];

    public function whatsappMessages(): HasMany
    {
        return $this->hasMany(WhatsAppMessage::class);
    }
}
