<?php

namespace App\Http\Controllers\CustomerSuccess;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index(Request $request): View
    {
        return view('customer-success.gestion_usuarios.index');
    }
}
