<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientPreregistration extends Model
{
    use BelongsToClinica;

    protected $fillable = [
        'clinica_id',
        'registration_link_id',
        'patient_id',
        'reviewed_by',
        'status',
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
        'procedimiento',
        'motivo_consulta',
        'alergias',
        'enfermedades',
        'medicamentos_actuales',
        'antecedentes_medicos',
        'observaciones',
        'foto',
        'consent_accepted_at',
        'ip_address',
        'user_agent',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'edad' => 'integer',
            'peso' => 'decimal:2',
            'altura' => 'decimal:2',
            'consent_accepted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function registrationLink(): BelongsTo
    {
        return $this->belongsTo(PatientRegistrationLink::class, 'registration_link_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
