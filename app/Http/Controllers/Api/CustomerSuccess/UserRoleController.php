<?php

namespace App\Http\Controllers\Api\CustomerSuccess;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::select(['id', 'name', 'email', 'created_at'])
            ->with('roles:id,name')
            ->orderBy('name')
            ->get();

        return response()->json($users);
    }

    public function assign(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'role' => 'required|string|in:Customer Success',
        ]);

        if ($user->hasRole($request->role)) {
            return response()->json([
                'message' => 'El usuario ya tiene este rol.',
            ], 422);
        }

        $user->assignRole($request->role);

        return response()->json([
            'message' => 'Rol asignado correctamente.',
            'user' => $user->load('roles:id,name'),
        ]);
    }

    public function remove(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'role' => 'required|string|in:Customer Success',
        ]);

        if (! $user->hasRole($request->role)) {
            return response()->json([
                'message' => 'El usuario no tiene este rol.',
            ], 422);
        }

        $user->removeRole($request->role);

        return response()->json([
            'message' => 'Rol removido correctamente.',
            'user' => $user->load('roles:id,name'),
        ]);
    }
}
