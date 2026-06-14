<?php

namespace App\Http\Controllers\Direcciones;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDireccionRequest;
use App\Http\Requests\UpdateDireccionRequest;
use App\Models\Cliente;
use App\Models\Direccion;
use App\Models\Plan;

class DireccionController extends Controller
{
    public function show(Direccion $direccion)
    {
        $direccion->load(['plan', 'dispositivos', 'cliente']);

        $usados = $direccion->dispositivos->count();
        $limite = $direccion->plan?->max_dispositivos ?? 0;

        return view('direcciones.show', compact('direccion', 'usados', 'limite'));
    }

    public function create(Cliente $cliente)
    {
        $planes = Plan::orderBy('nombre')->get();

        return view('direcciones.create', compact('cliente', 'planes'));
    }

    public function store(StoreDireccionRequest $request, Cliente $cliente)
    {
        $cliente->direcciones()->create($request->validated());

        return redirect()->route('clientes.show', $cliente)
            ->with('success', 'Inmueble agregado correctamente.');
    }

    public function edit(Direccion $direccion)
    {
        $planes = Plan::orderBy('nombre')->get();

        return view('direcciones.edit', compact('direccion', 'planes'));
    }

    public function update(UpdateDireccionRequest $request, Direccion $direccion)
    {
        $direccion->update($request->validated());

        return redirect()->route('clientes.show', ['cliente' => $direccion->cliente_id])
            ->with('success', 'Inmueble actualizado correctamente.');
    }

    public function destroy(Direccion $direccion)
    {
        $clienteId = $direccion->cliente_id;
        $direccion->delete();

        return redirect()->route('clientes.show', ['cliente' => $clienteId])
            ->with('success', 'Inmueble eliminado correctamente.');
    }
}
