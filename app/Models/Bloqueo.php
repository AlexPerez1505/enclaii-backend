<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;

class Bloqueo extends Model
{
    use BelongsToClinica;

    protected $table = 'bloqueos';

    protected $fillable = [
        'clinica_id',
        'label',
        'fecha',
        'hora',
        'hora_fin',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];
}
