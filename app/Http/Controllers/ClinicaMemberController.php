<?php

namespace App\Http\Controllers;

use App\Models\Clinica;
use App\Models\ClinicaInvitation;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ClinicaMemberController extends Controller
{
    public function storeInvitation(Request $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'rol' => ['required', Rule::in(['administrador', 'medico', 'recepcionista', 'asistente'])],
        ]);
        $email = Str::lower(trim($validated['email']));

        if ($email === Str::lower($user->email)) {
            return response()->json(['message' => 'Tú ya formas parte de esta clínica.'], 422);
        }

        if ($user->clinica->usuarios()->whereRaw('LOWER(email) = ?', [$email])->exists()) {
            return response()->json(['message' => 'Ese correo ya pertenece a la clínica.'], 422);
        }

        $pendingCount = $user->clinica->invitations()
            ->whereNull('accepted_at')
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->count();
        $memberCount = $user->clinica->usuarios()->count();

        if (($memberCount + $pendingCount) >= $user->clinicMemberLimit()) {
            $offer = $user->clinicMemberUpgradeOffer();
            $message = $offer['type'] === 'member_addon'
                ? 'Tu Red Médica alcanzó su límite. Compra una cuenta adicional por $5,000 MXN al mes.'
                : 'Tu plan alcanzó su límite. Cambia al Plan '.$offer['target_label'].' para agregar más cuentas.';

            return response()->json([
                'message' => $message,
                'code' => 'member_limit_reached',
                'member_limit' => $user->clinicMemberLimit(),
                'member_usage' => $memberCount + $pendingCount,
                'upgrade_offer' => $offer,
            ], 422);
        }

        if (User::query()->whereRaw('LOWER(email) = ?', [$email])->exists()) {
            return response()->json([
                'message' => 'Ese correo ya tiene una cuenta. Agrega un correo que todavía no esté registrado.',
            ], 422);
        }

        DB::transaction(function () use ($user, $email, $validated): void {
            $user->clinica->invitations()
                ->whereRaw('LOWER(email) = ?', [$email])
                ->whereNull('accepted_at')
                ->whereNull('revoked_at')
                ->update(['revoked_at' => now()]);

            $user->clinica->invitations()->create([
                'invited_by' => $user->id,
                'email' => $email,
                'rol' => $validated['rol'],
                'token_hash' => hash('sha256', Str::uuid()->toString()),
                'expires_at' => now()->addYears(10),
            ]);
        });

        return response()->json([
            'message' => 'Correo autorizado. Cuando la persona cree su cuenta quedará dentro de esta clínica.',
        ], 201);
    }

    public function destroyInvitation(Request $request, ClinicaInvitation $invitation): JsonResponse
    {
        abort_unless($invitation->clinica_id === $request->user()->clinica_id, 404);
        abort_if($invitation->accepted_at || $invitation->revoked_at, 422);

        $invitation->update(['revoked_at' => now()]);

        return response()->json(['message' => 'Invitación cancelada.']);
    }

    public function destroyMember(Request $request, User $member): JsonResponse
    {
        $owner = $request->user();
        abort_unless($member->clinica_id === $owner->clinica_id, 404);
        abort_if($member->id === $owner->id || $member->clinica_rol === 'propietario', 422);

        $personalClinic = Clinica::create([
            'nombre' => 'Clínica de '.$member->name,
        ]);

        $member->forceFill([
            'clinica_id' => $personalClinic->id,
            'clinica_rol' => 'propietario',
        ])->save();

        return response()->json(['message' => 'El integrante fue retirado de la clínica.']);
    }
}
