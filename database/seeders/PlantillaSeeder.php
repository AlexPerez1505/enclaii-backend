<?php

namespace Database\Seeders;

use App\Models\Plantilla;
use Illuminate\Database\Seeder;

class PlantillaSeeder extends Seeder
{
    /**
     * Plantillas predeterminadas del sistema (las mismas que estaban
     * hardcodeadas en resources/views/ia-reportes/redactar.blade.php).
     */
    public function run(): void
    {
        // Configuración visual por defecto del encabezado (logo / nombre / anatomía / firma)
        $cfg = [
            'logoImg'  => null,
            'anatImg'  => null,
            'clinic'   => '',
            'signName' => '',
            'signPos'  => 'center',
            'headH'    => 121,
            'logo'     => ['x' => 0,   'y' => 17, 'w' => 86,  'h' => 86],
            'name'     => ['x' => 100, 'y' => 28, 'w' => 466, 'h' => 64, 'fontSize' => 21],
            'anat'     => ['x' => 672, 'y' => 5,  'w' => 88,  'h' => 110],
        ];

        // ===== Plantillas de informe (con secciones de texto) =====
        $informe = [
            [
                'clave' => 'colonoscopia',
                'nombre' => 'Colonoscopia',
                'descripcion' => 'Preparación, hallazgos por segmento…',
                'tipo_estudio' => 'Colonoscopia',
                'titulo' => 'INFORME DE COLONOSCOPIA',
                'subtitulo' => 'COLONOSCOPIA',
                'secciones' => [
                    ['h' => 'INDICACIÓN', 'tipo' => 'p', 'ph' => 'Motivo del estudio…'],
                    ['h' => 'PREPARACIÓN', 'tipo' => 'p', 'ph' => 'Calidad de la preparación…'],
                    ['h' => 'SEDACIÓN', 'tipo' => 'p', 'ph' => 'Tipo y nivel de sedación…'],
                    ['h' => 'HALLAZGOS', 'tipo' => 'ul', 'ph' => 'Hallazgo por segmento (recto, sigmoides, colon…)'],
                    ['h' => 'IMPRESIÓN DIAGNÓSTICA', 'tipo' => 'p', 'ph' => 'Diagnóstico…'],
                    ['h' => 'PLAN Y RECOMENDACIONES', 'tipo' => 'ul', 'ph' => 'Recomendación…'],
                    ['h' => 'OBSERVACIONES', 'tipo' => 'p', 'ph' => 'Observaciones adicionales…'],
                ],
            ],
            [
                'clave' => 'gastroscopia',
                'nombre' => 'Gastroscopia',
                'descripcion' => 'Esófago, estómago, duodeno…',
                'tipo_estudio' => 'Gastroscopia',
                'titulo' => 'INFORME DE GASTROSCOPIA',
                'subtitulo' => 'GASTROSCOPIA',
                'secciones' => [
                    ['h' => 'INDICACIÓN', 'tipo' => 'p', 'ph' => 'Motivo del estudio…'],
                    ['h' => 'SEDACIÓN', 'tipo' => 'p', 'ph' => 'Tipo y nivel de sedación…'],
                    ['h' => 'HALLAZGOS', 'tipo' => 'ul', 'ph' => 'Esófago / estómago / duodeno…'],
                    ['h' => 'IMPRESIÓN DIAGNÓSTICA', 'tipo' => 'p', 'ph' => 'Diagnóstico…'],
                    ['h' => 'PLAN Y RECOMENDACIONES', 'tipo' => 'ul', 'ph' => 'Recomendación…'],
                    ['h' => 'OBSERVACIONES', 'tipo' => 'p', 'ph' => 'Observaciones adicionales…'],
                ],
            ],
            [
                'clave' => 'duodenoscopia',
                'nombre' => 'Duodenoscopia',
                'descripcion' => 'Duodeno, papila, vía biliar…',
                'tipo_estudio' => 'Duodenoscopia',
                'titulo' => 'INFORME DE DUODENOSCOPIA',
                'subtitulo' => 'DUODENOSCOPIA',
                'secciones' => [
                    ['h' => 'INDICACIÓN', 'tipo' => 'p', 'ph' => 'Motivo del estudio…'],
                    ['h' => 'SEDACIÓN', 'tipo' => 'p', 'ph' => 'Tipo y nivel de sedación…'],
                    ['h' => 'HALLAZGOS', 'tipo' => 'ul', 'ph' => 'Duodeno / papila / vía biliar…'],
                    ['h' => 'IMPRESIÓN DIAGNÓSTICA', 'tipo' => 'p', 'ph' => 'Diagnóstico…'],
                    ['h' => 'PLAN Y RECOMENDACIONES', 'tipo' => 'ul', 'ph' => 'Recomendación…'],
                    ['h' => 'OBSERVACIONES', 'tipo' => 'p', 'ph' => 'Observaciones adicionales…'],
                ],
            ],
            [
                'clave' => 'broncoscopia',
                'nombre' => 'Broncoscopia',
                'descripcion' => 'Árbol bronquial, tráquea, carina…',
                'tipo_estudio' => 'Broncoscopia',
                'titulo' => 'INFORME DE BRONCOSCOPIA',
                'subtitulo' => 'BRONCOSCOPIA',
                'secciones' => [
                    ['h' => 'INDICACIÓN', 'tipo' => 'p', 'ph' => 'Motivo del estudio…'],
                    ['h' => 'SEDACIÓN', 'tipo' => 'p', 'ph' => 'Tipo y nivel de sedación…'],
                    ['h' => 'HALLAZGOS', 'tipo' => 'ul', 'ph' => 'Árbol bronquial / tráquea / carina…'],
                    ['h' => 'IMPRESIÓN DIAGNÓSTICA', 'tipo' => 'p', 'ph' => 'Diagnóstico…'],
                    ['h' => 'PLAN Y RECOMENDACIONES', 'tipo' => 'ul', 'ph' => 'Recomendación…'],
                    ['h' => 'OBSERVACIONES', 'tipo' => 'p', 'ph' => 'Observaciones adicionales…'],
                ],
            ],
            [
                'clave' => 'blanco',
                'nombre' => 'En blanco',
                'descripcion' => 'Empieza desde cero',
                'tipo_estudio' => null,
                'titulo' => 'NUEVO REPORTE',
                'subtitulo' => '',
                'secciones' => [
                    ['h' => 'INTRODUCCIÓN', 'tipo' => 'p', 'ph' => 'Escribe aquí…'],
                    ['h' => 'DESARROLLO', 'tipo' => 'p', 'ph' => 'Escribe aquí…'],
                    ['h' => 'CONCLUSIÓN', 'tipo' => 'p', 'ph' => 'Escribe aquí…'],
                ],
            ],
        ];

        $orden = 1;
        foreach ($informe as $tpl) {
            $tplCfg = $cfg;
            if ($tpl['tipo_estudio'] && file_exists(public_path('images/' . $tpl['tipo_estudio'] . '.png'))) {
                $tplCfg['anatImg'] = '/images/' . $tpl['tipo_estudio'] . '.png';
            }
            Plantilla::updateOrCreate(
                ['clave' => $tpl['clave']],
                array_merge($tpl, [
                    'tipo_plantilla' => 'informe',
                    'columnas' => null,
                    'num_imagenes' => null,
                    'configuracion' => $tplCfg,
                    'solo_imagenes' => false,
                    'es_predeterminada' => true,
                    'orden' => $orden++,
                ])
            );
        }

        // ===== Plantillas de solo imágenes (sin texto en el cuerpo) =====
        $imagenes = [
            ['clave' => 'img2', 'nombre' => '2 columnas', 'columnas' => 2, 'num_imagenes' => 4],
            ['clave' => 'img3', 'nombre' => '3 columnas', 'columnas' => 3, 'num_imagenes' => 6],
            ['clave' => 'img4', 'nombre' => '4 columnas', 'columnas' => 4, 'num_imagenes' => 8],
            ['clave' => 'imgNone', 'nombre' => 'Sin imágenes', 'columnas' => 0, 'num_imagenes' => 0],
        ];

        $orden = 1;
        foreach ($imagenes as $tpl) {
            Plantilla::updateOrCreate(
                ['clave' => $tpl['clave']],
                [
                    'nombre' => $tpl['nombre'],
                    'descripcion' => null,
                    'tipo_plantilla' => 'imagenes',
                    'tipo_estudio' => null,
                    'titulo' => null,
                    'subtitulo' => null,
                    'secciones' => [],
                    'columnas' => $tpl['columnas'],
                    'num_imagenes' => $tpl['num_imagenes'],
                    'configuracion' => $cfg,
                    'solo_imagenes' => true,
                    'es_predeterminada' => true,
                    'orden' => $orden++,
                ]
            );
        }
    }
}
