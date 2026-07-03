<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoporteRequest extends Model
{
    use HasFactory;

    protected $table = 'soporte_requests';

    protected $fillable = [
        'user_id',
        'category',
        'subject',
        'description',
        'status',
    ];
}
