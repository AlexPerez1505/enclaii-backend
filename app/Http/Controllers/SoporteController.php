<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\View\View;

class SoporteController extends Controller
{
    public function index(): View
    {
        $latestTicket = Ticket::where('user_id', auth()->id())
            ->latest()
            ->first();

        return view('soporte.index', compact('latestTicket'));
    }
}
