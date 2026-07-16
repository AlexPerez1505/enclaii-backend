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

    public function anuncios(Request $request)
    {
        $query = Anuncio::with('user')->orderByDesc('created_at');

        if ($q = $request->input('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('titulo', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%"))
                    ->orWhere('tipo', 'like', "%{$q}%");
            });
        }

        if ($tipo = $request->input('tipo')) {
            $query->where('tipo', $tipo);
        }

        if ($canal = $request->input('canal')) {
            $query->whereJsonContains('canales', $canal);
        }

        if ($estado = $request->input('estado')) {
            if ($estado === 'activo') {
                $query->where('activo', true);
            } elseif ($estado === 'inactivo') {
                $query->where('activo', false)
                      ->where(fn ($sub) => $sub->whereNull('fecha_publicacion')->orWhere('fecha_publicacion', '<=', now()));
            } elseif ($estado === 'programado') {
                $query->where('activo', false)->where('fecha_publicacion', '>', now());
            }
        }

        $anuncios = $query->paginate(20)->withQueryString();

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
