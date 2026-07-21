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
        $ultimoNumero = Paciente::query()
            ->where('folio', 'like', 'P-%')
            ->get()
            ->map(function ($paciente) {
                return (int) preg_replace('/^P-(\d+)$/', '$1', $paciente->folio);
            })
            ->max() ?? 0;

        $siguienteNumero = $ultimoNumero + 1;

        $folio = 'P-' . str_pad($siguienteNumero, 3, '0', STR_PAD_LEFT);

        return view('pacientes.create', compact('folio'));
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
                'telefono' => ['nullable', 'string', 'max:50'],
                'email' => ['nullable', 'email', 'max:255'],
                'medico' => ['nullable', 'string', 'max:255'],
                'procedimiento' => ['nullable', 'string', 'max:255'],
                'anestesiologo' => ['nullable', 'string', 'max:255'],
                'referido_por' => ['nullable', 'string', 'max:255'],
                'equipo_utilizado' => ['nullable', 'string', 'max:255'],
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

    public function edit(Paciente $paciente)
    {
        return view('pacientes.edit', compact('paciente'));
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
            'telefono' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'medico' => ['nullable', 'string', 'max:255'],
            'procedimiento' => ['nullable', 'string', 'max:255'],
            'anestesiologo' => ['nullable', 'string', 'max:255'],
            'referido_por' => ['nullable', 'string', 'max:255'],
            'equipo_utilizado' => ['nullable', 'string', 'max:255'],
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

            $validated['foto'] = $request->file('foto')->store(
                'clinicas/'.$request->user()->clinica_id.'/pacientes',
                'public',

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
        $camposPermitidos = ['medico', 'procedimiento', 'anestesiologo', 'referido_por', 'equipo_utilizado'];

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
