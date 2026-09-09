<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinica;
use Illuminate\Database\Eloquent\Model;

class Procedimiento extends Model
{
    use BelongsToClinica;

    // Esto permite que podamos crear registros mediante el controlador
    protected $fillable = ['clinica_id', 'nombre'];
}