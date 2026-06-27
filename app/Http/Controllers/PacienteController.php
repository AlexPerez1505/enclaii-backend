<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PacienteController extends Controller
{
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
                'folio' => ['required', 'string', 'max:255', 'unique:pacientes,folio'],
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
                'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);

            if ($request->hasFile('foto')) {
                $validated['foto'] = $request->file('foto')->store('pacientes', 'public');
            }

            $paciente = Paciente::create($validated);

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
            'folio' => ['required', 'string', 'max:255', 'unique:pacientes,folio,' . $paciente->id],
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
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('foto')) {
            if ($paciente->foto && Storage::disk('public')->exists($paciente->foto)) {
                Storage::disk('public')->delete($paciente->foto);
            }

            $validated['foto'] = $request->file('foto')->store('pacientes', 'public');
        }

        $paciente->update($validated);

        return redirect()
            ->route('pacientes.index')
            ->with('success', 'Paciente actualizado correctamente.');
    }

    public function destroy(Paciente $paciente)
    {
        try {
            if ($paciente->foto && Storage::disk('public')->exists($paciente->foto)) {
                Storage::disk('public')->delete($paciente->foto);
            }

            $paciente->estudios()->each(function ($estudio) {
                $estudio->archivos()->each(function ($archivo) {
                    if ($archivo->path && Storage::disk('public')->exists($archivo->path)) {
                        Storage::disk('public')->delete($archivo->path);
                    }
                    $archivo->delete();
                });

                if ($estudio->reporte_path && Storage::disk('public')->exists($estudio->reporte_path)) {
                    Storage::disk('public')->delete($estudio->reporte_path);
                }

                if ($estudio->video_path && Storage::disk('public')->exists($estudio->video_path)) {
                    Storage::disk('public')->delete($estudio->video_path);
                }

                $estudio->delete();
            });

            $paciente->delete();

            return response()->json(['success' => true, 'message' => 'Paciente eliminado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()], 500);
        }
    }
}