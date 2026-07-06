<?php

namespace App\Http\Controllers\CustomerSuccess;

use App\Http\Controllers\Controller;
use App\Models\Anuncio;
use App\Models\CustomerSuccessAuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Customer Success home dashboard.
     */
    public function index(Request $request): View
    {
        $anunciosCount = Anuncio::count();
        $usuariosCs = User::role('Customer Success')->count();
        $auditLogs = CustomerSuccessAuditLog::with('user')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('customer-success.dashboard.index', compact(
            'anunciosCount', 'usuariosCs', 'auditLogs'
        ));
    }
}
