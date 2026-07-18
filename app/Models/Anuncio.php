<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anuncio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'contenido',
        'tipo',
        'publico_objetivo',
        'canales',
        'fecha_publicacion',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'fecha_publicacion' => 'datetime',
            'activo' => 'boolean',
            'canales' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
