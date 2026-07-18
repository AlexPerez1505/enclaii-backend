<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedimiento extends Model
{
    // Esto permite que podamos crear registros mediante el controlador
    protected $fillable = ['nombre'];
}