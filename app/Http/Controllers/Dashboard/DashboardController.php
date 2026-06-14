<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client;

class DashboardController extends Controller
{
    public function index()
    {
        $clients = Client::with(['addresses' => fn ($q) => $q->select('id', 'client_id', 'alias')])
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('dashboard.index', compact('clients'));
    }
}
