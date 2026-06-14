<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DispositivoResource;
use App\Models\Direccion;
use App\Models\Dispositivo;

class DispositivoController extends Controller
{
    public function index(int $direccion_id)
    {
        Direccion::findOrFail($direccion_id);

        $dispositivos = Dispositivo::where('direccion_id', $direccion_id)
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        return DispositivoResource::collection($dispositivos);
    }
}
