<?php

namespace App\Http\Controllers;

use App\Models\PacienteDocumento;

class PacienteDocumentoController extends Controller
{
    public function destroy(PacienteDocumento $pacienteDocumento)
    {
        media_delete($pacienteDocumento->path);

        $pacienteDocumento->delete();

        return response()->json(['success' => true]);
    }
}
