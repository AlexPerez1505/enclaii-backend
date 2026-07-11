<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use App\Models\CustomerSuccessAuditLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerSuccessController extends Controller
{
    public function dashboard()
    {
        return view('customer-success.dashboard.index', [
            'anunciosCount' => Anuncio::count(),
            'usuariosCs'    => User::role('Customer Success')->count(),
            'auditLogs'     => CustomerSuccessAuditLog::with('user')->latest()->limit(20)->get(),
        ]);
    }

    public function anuncios()
    {
        $anuncios = Anuncio::latest()->paginate(20);
        return view('customer-success.anuncios.index', compact('anuncios'));
    }

    public function gestionUsuarios()
    {
        return view('customer-success.gestion_usuarios.index');
    }

    public function users(): JsonResponse
    {
        $users = User::with('roles')->get()->map(fn (User $u) => [
            'id'    => $u->id,
            'name'  => $u->name,
            'email' => $u->email,
            'roles' => $u->roles->map(fn ($r) => ['name' => $r->name]),
        ]);

        return response()->json($users);
    }

    public function assignRole(Request $request, User $user): JsonResponse
    {
        $request->validate(['role' => 'required|string']);
        $user->assignRole($request->role);
        return response()->json(['message' => 'Rol asignado correctamente.']);
    }

    public function removeRole(Request $request, User $user): JsonResponse
    {
        $request->validate(['role' => 'required|string']);
        $user->removeRole($request->role);
        return response()->json(['message' => 'Rol removido correctamente.']);
    }
}
