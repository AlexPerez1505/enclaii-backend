<?php

namespace App\Http\Controllers;

use App\Models\PacienteDocumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PacienteDocumentoController extends Controller
{
    public function destroy(PacienteDocumento $pacienteDocumento)
    {
        if ($pacienteDocumento->path && Storage::disk('public')->exists($pacienteDocumento->path)) {
            Storage::disk('public')->delete($pacienteDocumento->path);
        }

        $pacienteDocumento->delete();

        return response()->json(['success' => true]);
    }
}
