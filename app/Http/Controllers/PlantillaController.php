<?php

namespace App\Http\Controllers;

use App\Models\Plantilla;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlantillaController extends Controller
{
    /**
     * Actualiza la configuración visual / de imágenes de una plantilla.
     */
    public function update(Request $request, string $clave): JsonResponse
    {
        $plantilla = Plantilla::where('clave', $clave)->firstOrFail();

        $validated = $request->validate([
            'configuracion' => ['nullable', 'array'],
            'columnas' => ['nullable', 'integer', 'min:0', 'max:12'],
            'num_imagenes' => ['nullable', 'integer', 'min:0', 'max:48'],
        ]);

        if (array_key_exists('configuracion', $validated)) {
            $plantilla->configuracion = $validated['configuracion'];
        }
        if (array_key_exists('columnas', $validated)) {
            $plantilla->columnas = $validated['columnas'];
        }
        if (array_key_exists('num_imagenes', $validated)) {
            $plantilla->num_imagenes = $validated['num_imagenes'];
        }

        $plantilla->save();

        return response()->json([
            'ok' => true,
            'message' => 'Plantilla actualizada.',
        ]);
    }
}
