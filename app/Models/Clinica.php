<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clinica extends Model
{
    protected $fillable = [
        'nombre',
        'is_shared',
    ];

    protected function casts(): array
    {
        return [
            'is_shared' => 'boolean',
        ];
    }

    public static function shared(): self
    {
        return static::query()->firstOrCreate(
            ['is_shared' => true],
            ['nombre' => 'Espacio compartido'],
        );
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function pacientes(): HasMany
    {
        return $this->hasMany(Paciente::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(ClinicaInvitation::class);
    }
}
