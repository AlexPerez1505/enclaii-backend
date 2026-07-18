<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\View\View;

class SoporteController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $clinicaData = implode(' — ', array_filter([
            $user->clinica_nombre,
            $user->razon_social,
            $user->rfc,
        ]));

        $latestTicket = Ticket::where('user_id', $user->id)
            ->latest()
            ->first();

        $operationFolio = $this->generarFolioTicket();
        $operationDate = now()->format('d/m/Y H:i');
        $perfilIncompleto = empty($user->clinica_nombre);

        return view('soporte.index', compact('latestTicket', 'clinicaData', 'operationFolio', 'operationDate', 'perfilIncompleto'));
    }

    private function generarFolioTicket(): string
    {
        $ultimoId = (int) Ticket::max('id') + 1;

        do {
            $folio = 'T-' . str_pad((string) $ultimoId, 4, '0', STR_PAD_LEFT);
            $ultimoId++;
        } while (Ticket::where('operation_folio', $folio)->exists());

        return $folio;
    }
}
