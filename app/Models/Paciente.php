<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    use BelongsToClinica;

    protected $fillable = [
        'clinica_id',
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
<<<<<<< HEAD
=======
        'enfermedad',
>>>>>>> origin/main
        'alergias',
        'enfermedades',
        'medicamentos_actuales',
        'antecedentes_medicos',
        'foto',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'edad' => 'integer',
        'peso' => 'decimal:2',
        'altura' => 'decimal:2',
    ];

    public function estudios(): HasMany
    {
        return $this->hasMany(Estudio::class);
    }

    public function whatsappMessages(): HasMany
    {
        return $this->hasMany(WhatsAppMessage::class);
    }
}
