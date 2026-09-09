<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    use BelongsToClinica;

    protected $fillable = ['clinica_id', 'nombre', 'activa'];
}
