<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cliente;

class DashboardController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with(['direcciones' => fn ($q) => $q->select('id', 'cliente_id', 'alias')])
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        return view('dashboard.index', compact('clientes'));
    }
}
