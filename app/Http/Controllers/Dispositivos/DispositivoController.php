<?php

namespace App\Http\Controllers\Dispositivos;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDispositivoRequest;
use App\Http\Requests\UpdateDispositivoRequest;
use App\Models\Direccion;
use App\Models\Dispositivo;

class DispositivoController extends Controller
{
    private function tipos(): array
    {
        return [
            'luz'            => 'Luz',
            'persiana'       => 'Persiana',
            'sensor_puerta'  => 'Sensor de puerta',
            'sensor_ventana' => 'Sensor de ventana',
            'camara'         => 'Cámara',
            'calefaccion'    => 'Calefacción',
        ];
    }

    public function create(Direccion $direccion)
    {
        $direccion->load('plan');

        $usados = $direccion->dispositivos()->count();
        $limite = $direccion->plan?->max_dispositivos ?? 0;

        if ($limite > 0 && $usados >= $limite) {
            return redirect()->route('direcciones.show', $direccion)
                ->with('error', "El plan {$direccion->plan->nombre} permite un máximo de {$limite} dispositivos.");
        }

        return view('dispositivos.create', [
            'direccion' => $direccion,
            'tipos'     => $this->tipos(),
            'usados'    => $usados,
            'limite'    => $limite,
        ]);
    }

    public function store(StoreDispositivoRequest $request, Direccion $direccion)
    {
        $direccion->load('plan');

        if (($direccion->plan?->max_dispositivos ?? 0) > 0 && $direccion->dispositivos()->count() >= $direccion->plan->max_dispositivos) {
            return redirect()->route('direcciones.show', $direccion)
                ->with('error', 'Límite de dispositivos alcanzado para este plan.');
        }

        $estadoInicial = match($request->tipo) {
            'persiana', 'sensor_puerta', 'sensor_ventana' => 'cerrado',
            'camara'                                       => 'inactivo',
            default                                        => 'apagado',
        };

        $direccion->dispositivos()->create([
            ...$request->validated(),
            'estado' => $estadoInicial,
        ]);

        return redirect()->route('direcciones.show', $direccion)
            ->with('success', 'Dispositivo agregado correctamente.');
    }

    public function edit(Dispositivo $dispositivo)
    {
        return view('dispositivos.edit', [
            'dispositivo' => $dispositivo,
            'tipos'       => $this->tipos(),
        ]);
    }

    public function update(UpdateDispositivoRequest $request, Dispositivo $dispositivo)
    {
        $dispositivo->update($request->validated());

        return redirect()->route('direcciones.show', $dispositivo->direccion_id)
            ->with('success', 'Dispositivo actualizado correctamente.');
    }

    public function destroy(Dispositivo $dispositivo)
    {
        $direccionId = $dispositivo->direccion_id;
        $dispositivo->delete();

        return redirect()->route('direcciones.show', $direccionId)
            ->with('success', 'Dispositivo eliminado correctamente.');
    }
}
