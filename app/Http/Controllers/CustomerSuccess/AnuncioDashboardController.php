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
        $anuncios = Anuncio::with('user')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('customer-success.anuncios.index', compact('anuncios'));
    }
}
