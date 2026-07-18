<?php

namespace App\Http\Controllers\CustomerSuccess;

use App\Http\Controllers\Controller;
use App\Models\Anuncio;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnuncioDashboardController extends Controller
{
    /**
     * Display the Customer Success anuncios dashboard.
     */
    public function index(Request $request): View
    {
        $query = Anuncio::with('user')->orderByDesc('created_at');

        if ($q = $request->input('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('titulo', 'like', "%{$q}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%"))
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
            match ($estado) {
                'activo'     => $query->where('activo', true),
                'inactivo'   => $query->where('activo', false)->where(fn($q) => $q->whereNull('fecha_publicacion')->orWhere('fecha_publicacion', '<=', now())),
                'programado' => $query->where('activo', false)->where('fecha_publicacion', '>', now()),
                default      => null,
            };
        }

        $anuncios = $query->paginate(20)->withQueryString();

        return view('customer-success.anuncios.index', compact('anuncios'));
    }
}
