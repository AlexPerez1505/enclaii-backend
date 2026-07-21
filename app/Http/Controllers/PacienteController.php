<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Services\ActivityLogger;
use App\Services\MediaPathService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PacienteController extends Controller
{
    public function __construct(
        private readonly ActivityLogger $activity,
        private readonly MediaPathService $mediaPaths,
    ) {}

    public function index()
    {
        $pacientes = Paciente::latest()->get();

        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {

        $listaProcedimientos = \App\Models\Procedimiento::all();
        $listaAnestesiologos = \App\Models\Anestesiologo::query()
            ->where('clinica_id', request()->user()->clinica_id)
            ->where('activo', true)
            ->orderBy('apellido_paterno')
            ->orderBy('nombres')
            ->get();
        $listaMedicos = \App\Models\Medico::query()
            ->where('clinica_id', request()->user()->clinica_id)
            ->where('activo', true)
            ->orderBy('apellido_paterno')
            ->orderBy('nombres')
            ->get();


        $ultimoNumero = Paciente::query()
            ->where('folio', 'like', 'P-%')
            ->get()
            ->map(function ($paciente) {
                return (int) preg_replace('/^P-(\d+)$/', '$1', $paciente->folio);
            })
            ->max() ?? 0;

        $siguienteNumero = $ultimoNumero + 1;

        $folio = 'P-' . str_pad($siguienteNumero, 3, '0', STR_PAD_LEFT);

        return view('pacientes.create', compact('folio', 'listaProcedimientos', 'listaAnestesiologos', 'listaMedicos'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'folio' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('pacientes', 'folio')
                        ->where('clinica_id', $request->user()->clinica_id),
                ],
                'nombre_completo' => ['required', 'string', 'max:255'],
                'identificacion' => ['nullable', 'string', 'max:255'],
                'fecha_nacimiento' => ['nullable', 'date'],
                'edad' => ['nullable', 'integer', 'min:0', 'max:150'],
                'peso' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
                'altura' => ['nullable', 'numeric', 'min:0', 'max:9.99'],
                'sexo' => ['nullable', 'string', 'max:50'],
                'direccion' => ['nullable', 'string', 'max:255'],
                'telefono' => ['nullable', 'regex:/^\d{0,10}$/'],
                'email' => ['nullable', 'email', 'max:255'],
                'medico' => ['nullable', 'string', 'max:255'],
                'procedimiento' => ['nullable', 'string', 'max:255'],
                'anestesiologo' => ['nullable', 'string', 'max:255'],
                'diagnostico_preliminar' => ['nullable', 'string'],
                'enfermedad' => ['nullable', 'string'],
                'alergias' => ['nullable', 'string'],
                'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                'estudios_archivos.*' => ['nullable', 'file', 'max:20480'],
            ]);

            $paciente = Paciente::create(collect($validated)->except('estudios_archivos', 'foto')->toArray());

            if ($request->hasFile('foto')) {
                $paciente->update([
                    'foto' => media_store(
                        $request->file('foto'),
                        $this->mediaPaths->patientProfile($paciente)
                    ),
                ]);
            }
            $this->activity->record(
                'patient_created',
                'patients',
                'Registró al paciente '.$paciente->folio,
                $paciente,
                request: $request,
            );

            if ($request->hasFile('estudios_archivos')) {
                foreach ($request->file('estudios_archivos') as $archivo) {
                    $path = media_store($archivo, $this->mediaPaths->patientDocuments($paciente));
                    \App\Models\PacienteDocumento::create([
                        'paciente_id' => $paciente->id,
                        'path' => $path,
                        'nombre_original' => $archivo->getClientOriginalName(),
                        'mime_type' => $archivo->getMimeType(),
                        'size_bytes' => $archivo->getSize(),
                    ]);
                }
            }

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'paciente_id' => $paciente->id,
                    'redirect_url' => route('agendar', ['paciente_id' => $paciente->id]),
                ]);
            }

            return redirect()
                ->route('agendar', ['paciente_id' => $paciente->id])
                ->with('success', 'Paciente registrado correctamente.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al registrar paciente: ' . $e->getMessage());
        }
    }

    public function show(Paciente $paciente)
    {
        return redirect()->route('pacientes.edit', $paciente);
    }

    // AQUÍ AGREGAS TU NUEVO MÉTODO edit
    public function edit(Paciente $paciente)
    {
        // Obtenemos todos los procedimientos para llenar el <select>
        $listaProcedimientos = \App\Models\Procedimiento::all();
        $listaAnestesiologos = \App\Models\Anestesiologo::query()
            ->where('clinica_id', request()->user()->clinica_id)
            ->where('activo', true)
            ->orderBy('apellido_paterno')
            ->orderBy('nombres')
            ->get();
        $listaMedicos = \App\Models\Medico::query()
            ->where('clinica_id', request()->user()->clinica_id)
            ->where('activo', true)
            ->orderBy('apellido_paterno')
            ->orderBy('nombres')
            ->get();
        
        // Asegúrate de poner la ruta correcta de tu vista (ej: 'pacientes.edit')
        return view('pacientes.edit', compact('paciente', 'listaProcedimientos', 'listaAnestesiologos', 'listaMedicos'));
    }




    public function update(Request $request, Paciente $paciente)
    {
        $validated = $request->validate([
            'folio' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pacientes', 'folio')
                    ->where('clinica_id', $request->user()->clinica_id)
                    ->ignore($paciente->id),
            ],
            'nombre_completo' => ['required', 'string', 'max:255'],
            'identificacion' => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'edad' => ['nullable', 'integer', 'min:0', 'max:150'],
            'peso' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'altura' => ['nullable', 'numeric', 'min:0', 'max:9.99'],
            'sexo' => ['nullable', 'string', 'max:50'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'regex:/^\d{0,10}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'medico' => ['nullable', 'string', 'max:255'],
            'procedimiento' => ['nullable', 'string', 'max:255'],
            'diagnostico_preliminar' => ['nullable', 'string'],
            'enfermedad' => ['nullable', 'string'],
            'alergias' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'estudios_archivos.*' => ['nullable', 'file', 'max:20480'],
        ]);

        if ($request->hasFile('foto')) {
            media_delete($paciente->foto);

            $validated['foto'] = media_store(
                $request->file('foto'),
                $this->mediaPaths->patientProfile($paciente)
            );
        }

        $paciente->update($validated);
        $this->activity->record(
            'patient_updated',
            'patients',
            'Actualizó al paciente '.$paciente->folio,
            $paciente,
            request: $request,
        );

        \Log::info('PACIENTE UPDATE - hasFile estudios_archivos: ' . ($request->hasFile('estudios_archivos') ? 'SI' : 'NO'));
        \Log::info('PACIENTE UPDATE - allFiles: ' . json_encode(array_keys($request->allFiles())));

        if ($request->hasFile('estudios_archivos')) {
            foreach ($request->file('estudios_archivos') as $archivo) {
                \Log::info('Guardando archivo: ' . $archivo->getClientOriginalName());
                $path = media_store($archivo, $this->mediaPaths->patientDocuments($paciente));
                \App\Models\PacienteDocumento::create([
                    'paciente_id' => $paciente->id,
                    'path' => $path,
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'mime_type' => $archivo->getMimeType(),
                    'size_bytes' => $archivo->getSize(),
                ]);
            }
        }

        if ($request->ajax() || $request->expectsJson() || $request->hasHeader('X-Requested-With')) {
            return response()->json(['success' => true]);
        }

        return redirect()
            ->route('pacientes.index')
            ->with('success', 'Paciente actualizado correctamente.');
    }

    public function addMedico(Request $request, Paciente $paciente)
    {
        $validated = $request->validate([
            'medico' => ['required', 'string', 'max:255'],
        ]);

        $paciente->medico = $validated['medico'];
        $paciente->save();

        return response()->json([
            'success' => true,
            'medico' => $paciente->medico,
        ]);
    }

    public function updateCampo(Request $request, Paciente $paciente)
    {
    $camposPermitidos = ['medico', 'procedimiento', 'anestesiologo'];

    $validated = $request->validate([
        'campo' => ['required', 'string', Rule::in($camposPermitidos)],
        'valor' => ['required', 'string', 'max:255'],
    ]);

    $paciente->{$validated['campo']} = $validated['valor'];
    $paciente->save();

    return response()->json([
        'success' => true,
        'campo' => $validated['campo'],
        'valor' => $paciente->{$validated['campo']},
    ]);
    }

    public function storeProcedimiento(Request $request) 
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:procedimientos,nombre']
        ]);

        $procedimiento = \App\Models\Procedimiento::create([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'success' => true,
            'procedimiento' => $procedimiento
        ]);
    }

    public function updateProcedimiento(Request $request, \App\Models\Procedimiento $procedimiento)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:procedimientos,nombre,'.$procedimiento->id]
        ]);

        $procedimiento->update(['nombre' => $request->nombre]);

        return response()->json([
            'success' => true,
            'message' => 'Procedimiento actualizado.',
            'procedimiento' => $procedimiento
        ]);
    }

    public function destroyProcedimiento(\App\Models\Procedimiento $procedimiento)
    {
        $procedimiento->delete();

        return response()->json([
            'success' => true,
            'message' => 'Procedimiento eliminado.',
        ]);
    }

    public function storeAnestesiologo(Request $request)
    {
        $validated = $request->validate([
            'nombres' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['nullable', 'string', 'max:255'],
            'apellido_materno' => ['nullable', 'string', 'max:255'],
            'especialidad' => ['nullable', 'string', 'max:255'],
            'cedula_profesional' => ['nullable', 'string', 'max:255'],
            'correo' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $anestesiologo = \App\Models\Anestesiologo::create([
            'clinica_id' => $request->user()->clinica_id,
            'nombres' => $validated['nombres'],
            'apellido_paterno' => $validated['apellido_paterno'] ?? null,
            'apellido_materno' => $validated['apellido_materno'] ?? null,
            'especialidad' => $validated['especialidad'] ?? null,
            'cedula_profesional' => $validated['cedula_profesional'] ?? null,
            'correo' => $validated['correo'] ?? null,
            'telefono' => $validated['telefono'] ?? null,
            'activo' => $request->boolean('activo', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Anestesiólogo guardado.',
            'anestesiologo' => $anestesiologo,
        ]);
    }

    public function updateAnestesiologo(Request $request, \App\Models\Anestesiologo $anestesiologo)
    {
        $validated = $request->validate([
            'nombres' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['nullable', 'string', 'max:255'],
            'apellido_materno' => ['nullable', 'string', 'max:255'],
            'especialidad' => ['nullable', 'string', 'max:255'],
            'cedula_profesional' => ['nullable', 'string', 'max:255'],
            'correo' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $anestesiologo->update([
            'nombres' => $validated['nombres'],
            'apellido_paterno' => $validated['apellido_paterno'] ?? null,
            'apellido_materno' => $validated['apellido_materno'] ?? null,
            'especialidad' => $validated['especialidad'] ?? null,
            'cedula_profesional' => $validated['cedula_profesional'] ?? null,
            'correo' => $validated['correo'] ?? null,
            'telefono' => $validated['telefono'] ?? null,
            'activo' => $request->boolean('activo', false),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Anestesiólogo actualizado.',
            'anestesiologo' => $anestesiologo,
        ]);
    }

    public function destroyAnestesiologo(\App\Models\Anestesiologo $anestesiologo)
    {
        $anestesiologo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Anestesiólogo eliminado.',
        ]);
    }

    public function storeMedico(Request $request)
    {
        $validated = $request->validate([
            'nombres' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['nullable', 'string', 'max:255'],
            'apellido_materno' => ['nullable', 'string', 'max:255'],
            'especialidad' => ['nullable', 'string', 'max:255'],
            'cedula_profesional' => ['nullable', 'string', 'max:255'],
            'correo' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $medico = \App\Models\Medico::create([
            'clinica_id' => $request->user()->clinica_id,
            'nombres' => $validated['nombres'],
            'apellido_paterno' => $validated['apellido_paterno'] ?? null,
            'apellido_materno' => $validated['apellido_materno'] ?? null,
            'especialidad' => $validated['especialidad'] ?? null,
            'cedula_profesional' => $validated['cedula_profesional'] ?? null,
            'correo' => $validated['correo'] ?? null,
            'telefono' => $validated['telefono'] ?? null,
            'activo' => $request->boolean('activo', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Médico guardado.',
            'medico' => $medico,
        ]);
    }

    public function updateMedico(Request $request, \App\Models\Medico $medico)
    {
        $validated = $request->validate([
            'nombres' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['nullable', 'string', 'max:255'],
            'apellido_materno' => ['nullable', 'string', 'max:255'],
            'especialidad' => ['nullable', 'string', 'max:255'],
            'cedula_profesional' => ['nullable', 'string', 'max:255'],
            'correo' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $medico->update([
            'nombres' => $validated['nombres'],
            'apellido_paterno' => $validated['apellido_paterno'] ?? null,
            'apellido_materno' => $validated['apellido_materno'] ?? null,
            'especialidad' => $validated['especialidad'] ?? null,
            'cedula_profesional' => $validated['cedula_profesional'] ?? null,
            'correo' => $validated['correo'] ?? null,
            'telefono' => $validated['telefono'] ?? null,
            'activo' => $request->boolean('activo', false),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Médico actualizado.',
            'medico' => $medico,
        ]);
    }

    public function destroyMedico(\App\Models\Medico $medico)
    {
        $medico->delete();

        return response()->json([
            'success' => true,
            'message' => 'Médico eliminado.',
        ]);
    }

    public function storeSala(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'activa' => ['nullable', 'boolean'],
        ]);

        $sala = \App\Models\Sala::create([
            'clinica_id' => $request->user()->clinica_id,
            'nombre' => $validated['nombre'],
            'activa' => $request->boolean('activa', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sala guardada.',
            'sala' => $sala,
        ]);
    }

    public function updateSala(Request $request, \App\Models\Sala $sala)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'activa' => ['nullable', 'boolean'],
        ]);

        $sala->update([
            'nombre' => $validated['nombre'],
            'activa' => $request->boolean('activa', false),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sala actualizada.',
            'sala' => $sala,
        ]);
    }

    public function destroySala(\App\Models\Sala $sala)
    {
        $sala->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sala eliminada.',
        ]);
    }

    public function destroy(Paciente $paciente)
    {
        try {
            media_delete($paciente->foto);

            $paciente->estudios()->each(function ($estudio) {
                $estudio->archivos()->each(function ($archivo) {
                    media_delete($archivo->path);
                    $archivo->delete();
                });

                media_delete($estudio->reporte_path);
                media_delete($estudio->video_path);

                $estudio->delete();
            });

            $paciente->delete();
            $this->activity->record(
                'patient_deleted',
                'patients',
                'Eliminó al paciente '.$paciente->folio,
                $paciente,
            );

            return response()->json(['success' => true, 'message' => 'Paciente eliminado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()], 500);
        }
    }
}
