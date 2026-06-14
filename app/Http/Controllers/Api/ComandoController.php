<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreComandoRequest;
use App\Http\Resources\DispositivoResource;
use App\Models\ComandoLog;
use App\Models\Dispositivo;

class ComandoController extends Controller
{
    /**
     * Registra un comando sobre un dispositivo y actualiza su estado.
     *
     * Usado por el panel Vue para encender/apagar/abrir/cerrar dispositivos.
     * El Raspberry Pi lee el nuevo estado vía GET /api/dispositivos/{id}.
     *
     * @param StoreComandoRequest $request
     */
    public function store(StoreComandoRequest $request)
    {
        $dispositivo = Dispositivo::findOrFail($request->dispositivo_id);

        $estadoNuevo = match($request->accion) {
            'encender'   => 'encendido',
            'apagar'     => 'apagado',
            'abrir'      => 'abierto',
            'cerrar'     => 'cerrado',
            'activar'    => 'activo',
            'desactivar' => 'inactivo',
        };

        $dispositivo->update(['estado' => $estadoNuevo]);

        ComandoLog::create([
            'dispositivo_id' => $dispositivo->id,
            'accion'         => $request->accion,
            'exitoso'        => true,
        ]);

        return response()->json([
            'dispositivo' => new DispositivoResource($dispositivo),
            'accion'      => $request->accion,
        ]);
    }
}
