<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DispositivoResource;
use App\Models\Dispositivo;

class DispositivoController extends Controller
{
    /**
     * Lista todos los dispositivos de una dirección con su estado actual.
     *
     * El Raspberry Pi consume este endpoint para sincronizar el estado
     * del hardware con la base de datos.
     *
     * @param int $direccion_id
     */
    public function index(int $direccion_id)
    {
        $dispositivos = Dispositivo::where('direccion_id', $direccion_id)
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        return DispositivoResource::collection($dispositivos);
    }
}
