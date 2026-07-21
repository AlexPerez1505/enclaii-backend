<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\PatientPreregistration;
use App\Models\PatientRegistrationLink;
use App\Services\ActivityLogger;
use App\Services\MediaPathService;
use App\Services\PatientFolioGenerator;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QrRegistrationController extends Controller
{
    public function __construct(
        private readonly ActivityLogger $activity,
        private readonly PatientFolioGenerator $folioGenerator,
        private readonly MediaPathService $mediaPaths,
    ) {}

    public function index(Request $request): View
    {
        $qrSettings = $request->user()->resolvedSettings();
        $links = PatientRegistrationLink::query()
            ->with(['creator', 'preregistration'])
            ->whereNull('archived_at')
            ->latest()
            ->limit(50)
            ->get();

        $preregistrations = PatientPreregistration::query()
            ->with(['registrationLink.creator', 'patient', 'reviewer'])
            ->latest()
            ->limit(30)
            ->get();

        $possibleDuplicates = ($qrSettings['qr_duplicate_check'] ?? true)
            ? $preregistrations
                ->filter(fn (PatientPreregistration $item) => $item->status === 'pending')
                ->mapWithKeys(fn (PatientPreregistration $item): array => [
                    $item->id => $this->hasPossibleDuplicate($item),
                ])
            : collect();

        $selectedLinkId = (int) ($request->query('qr') ?: session('new_qr_link_id'));
        $currentLink = $links->firstWhere('id', $selectedLinkId)
            ?? $links->first(fn (PatientRegistrationLink $link) => $link->isAvailable())
            ?? $links->first();

        return view('qr.index', compact('links', 'currentLink', 'preregistrations', 'possibleDuplicates', 'qrSettings'));
    }

    public function store(Request $request): RedirectResponse
    {
        $qrSettings = $request->user()->resolvedSettings();
        $validated = $request->validate([
            'expires_in_hours' => ['nullable', 'integer', 'in:24,48,168'],
            'patient_message' => ['nullable', 'string', 'max:150'],
        ]);
        $expiresInHours = (int) ($validated['expires_in_hours']
            ?? $qrSettings['qr_default_expiration_hours']
            ?? 48);
        $patientMessage = array_key_exists('patient_message', $validated)
            ? $validated['patient_message']
            : ($qrSettings['qr_default_patient_message'] ?? null);

        $token = Str::random(64);
        $link = PatientRegistrationLink::create([
            'clinica_id' => $request->user()->clinica_id,
            'created_by' => $request->user()->id,
            'token' => $token,
            'token_hash' => hash('sha256', $token),
            'status' => 'active',
            'patient_message' => $patientMessage ?: null,
            'expires_at' => now()->addHours($expiresInHours),
        ]);

        $this->activity->record(
            'patient_qr_created',
            'patients',
            'Generó un QR de pre-registro de paciente',
            $link,
            ['expires_at' => $link->expires_at->toIso8601String()],
            request: $request,
        );

        return redirect()
            ->route('qr.index')
            ->with('success', 'Código QR generado correctamente.')
            ->with('new_qr_link_id', $link->id);
    }

    public function image(PatientRegistrationLink $link): Response
    {
        abort_if($link->archived_at, 404);

        $url = route('qr.public.show', ['token' => $link->token]);
        $qrCode = new QrCode(
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 360,
            margin: 18,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(6, 16, 50),
            backgroundColor: new Color(255, 255, 255),
        );
        $result = (new SvgWriter)->write($qrCode);

        return response($result->getString(), 200, [
            'Content-Type' => $result->getMimeType(),
            'Cache-Control' => 'private, no-store, max-age=0',
            'Content-Disposition' => request()->boolean('download')
                ? 'attachment; filename="enclaii-registro-'.$link->id.'.svg"'
                : 'inline',
        ]);
    }

    public function destroy(Request $request, PatientRegistrationLink $link): RedirectResponse
    {
        if ($link->status !== 'active' || $link->preregistration()->exists()) {
            return back()->with('error', 'Este QR ya no puede cancelarse.');
        }

        $link->update([
            'status' => 'revoked',
            'revoked_at' => now(),
        ]);
        $this->activity->record(
            'patient_qr_revoked',
            'patients',
            'Canceló un QR de pre-registro',
            $link,
            request: $request,
        );

        return back()->with('success', 'Código QR cancelado.');
    }

    public function archive(Request $request, PatientRegistrationLink $link): RedirectResponse
    {
        abort_if($link->archived_at, 404);

        $updates = ['archived_at' => now()];

        if ($link->status === 'active') {
            $updates['status'] = 'revoked';
            $updates['revoked_at'] = now();
        }

        $link->update($updates);
        $this->activity->record(
            'patient_qr_archived',
            'patients',
            'Eliminó un QR del historial visible',
            $link,
            request: $request,
        );

        return back()->with('success', 'Código QR eliminado de la lista.');
    }

    public function accept(
        Request $request,
        PatientPreregistration $preregistration,
    ): RedirectResponse {
        DB::transaction(function () use ($request, $preregistration): void {
            $record = PatientPreregistration::query()
                ->whereKey($preregistration->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless($record->status === 'pending', 422, 'Este pre-registro ya fue revisado.');
            $qrSettings = $request->user()->resolvedSettings();

            if (
                ($qrSettings['qr_duplicate_check'] ?? true)
                && ($qrSettings['qr_duplicate_action'] ?? 'warn') === 'block_acceptance'
                && $this->hasPossibleDuplicate($record)
            ) {
                abort(422, 'Existe un paciente con el mismo teléfono o correo. Revisa el expediente existente antes de aceptar este pre-registro.');
            }

            $patient = Paciente::create([
                'clinica_id' => $request->user()->clinica_id,
                'folio' => $this->folioGenerator->next($request->user()->clinica_id),
                'nombre_completo' => $record->nombre_completo,
                'identificacion' => $record->identificacion,
                'fecha_nacimiento' => $record->fecha_nacimiento,
                'edad' => $record->edad,
                'peso' => $record->peso,
                'altura' => $record->altura,
                'sexo' => $record->sexo,
                'direccion' => $record->direccion,
                'telefono' => $record->telefono,
                'email' => $record->email,
                'medico' => $request->user()->name,
                'procedimiento' => $record->procedimiento,
                'diagnostico_preliminar' => $record->motivo_consulta,
                'alergias' => $record->alergias,
                'enfermedades' => $record->enfermedades,
                'medicamentos_actuales' => $record->medicamentos_actuales,
                'antecedentes_medicos' => $record->antecedentes_medicos,
                'foto' => null,
            ]);
            $photoPath = $this->movePatientPhotoToProfile($record->foto, $patient);

            if ($photoPath) {
                $patient->update(['foto' => $photoPath]);
            }

            $record->update([
                'status' => 'accepted',
                'patient_id' => $patient->id,
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
                'foto' => $photoPath,
            ]);

            $this->activity->record(
                'patient_preregistration_accepted',
                'patients',
                'Aceptó el pre-registro de '.$patient->nombre_completo,
                $patient,
                ['preregistration_id' => $record->id],
                request: $request,
            );
        });

        return back()->with('success', 'Pre-registro aceptado y expediente creado.');
    }

    private function movePatientPhotoToProfile(?string $currentPath, Paciente $patient): ?string
    {
        if (! $currentPath) {
            return null;
        }

        $extension = pathinfo($currentPath, PATHINFO_EXTENSION) ?: 'jpg';
        $targetPath = $this->mediaPaths->patientProfile($patient).'/profile-'.Str::random(12).'.'.$extension;
        $disk = Storage::disk(media_disk());

        if (! $disk->exists($currentPath)) {
            return $currentPath;
        }

        $disk->copy($currentPath, $targetPath);
        $disk->delete($currentPath);

        return $targetPath;
    }

    public function reject(
        Request $request,
        PatientPreregistration $preregistration,
    ): RedirectResponse {
        $photoToDelete = DB::transaction(function () use ($request, $preregistration): ?string {
            $record = PatientPreregistration::query()
                ->whereKey($preregistration->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless($record->status === 'pending', 422, 'Este pre-registro ya fue revisado.');

            $photo = $record->foto;
            $record->update([
                'status' => 'rejected',
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
                'foto' => null,
            ]);

            return $photo;
        });

        if ($photoToDelete) {
            media_delete($photoToDelete);
            Storage::disk('public')->delete($photoToDelete);
        }

        $preregistration->refresh();
        $this->activity->record(
            'patient_preregistration_rejected',
            'patients',
            'Rechazó el pre-registro de '.$preregistration->nombre_completo,
            $preregistration,
            request: $request,
        );

        return back()->with('success', 'Pre-registro rechazado.');
    }

    private function hasPossibleDuplicate(PatientPreregistration $preregistration): bool
    {
        return Paciente::query()
            ->where(function ($filter) use ($preregistration): void {
                if ($preregistration->email) {
                    $filter->orWhereRaw('LOWER(email) = ?', [Str::lower($preregistration->email)]);
                }
                if ($preregistration->telefono) {
                    $filter->orWhere('telefono', $preregistration->telefono);
                }
            })
            ->exists();
    }
}
