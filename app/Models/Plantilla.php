<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    protected $table = 'plantillas';

    protected $fillable = [
        'clave',
        'nombre',
        'descripcion',
        'tipo_plantilla',
        'tipo_estudio',
        'titulo',
        'subtitulo',
        'secciones',
        'columnas',
        'num_imagenes',
        'configuracion',
        'solo_imagenes',
        'es_predeterminada',
        'orden',
    ];

    protected $casts = [
        'secciones' => 'array',
        'configuracion' => 'array',
        'columnas' => 'integer',
        'num_imagenes' => 'integer',
        'solo_imagenes' => 'boolean',
        'es_predeterminada' => 'boolean',
        'orden' => 'integer',
    ];

    public function scopeInforme($query)
    {
        return $query->where('tipo_plantilla', 'informe');
    }

    public function scopeImagenes($query)
    {
        return $query->where('tipo_plantilla', 'imagenes');
    }
}
