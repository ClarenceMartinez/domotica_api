<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Dispositivo;
use Illuminate\Database\Seeder;

class DispositivoSeeder extends Seeder
{
    public function run(): void
    {
        $casaAna  = Cliente::where('email', 'ana.garcia@mail.com')->first()->direcciones()->where('alias', 'Casa')->first();
        $deptLuis = Cliente::where('email', 'luis.martinez@mail.com')->first()->direcciones()->where('alias', 'Departamento')->first();
        $ofLuis   = Cliente::where('email', 'luis.martinez@mail.com')->first()->direcciones()->where('alias', 'Oficina')->first();

        $dispositivos = [
            // Casa de Ana — plan Estándar
            ['direccion_id' => $casaAna->id,  'nombre' => 'Luz living',       'tipo' => 'luz',           'estado' => 'apagado'],
            ['direccion_id' => $casaAna->id,  'nombre' => 'Luz cocina',        'tipo' => 'luz',           'estado' => 'apagado'],
            ['direccion_id' => $casaAna->id,  'nombre' => 'Persiana dormitorio','tipo' => 'persiana',     'estado' => 'cerrado'],
            ['direccion_id' => $casaAna->id,  'nombre' => 'Puerta principal',  'tipo' => 'sensor_puerta', 'estado' => 'cerrado'],
            ['direccion_id' => $casaAna->id,  'nombre' => 'Calefacción',       'tipo' => 'calefaccion',   'estado' => 'apagado'],

            // Departamento de Luis — plan Básico
            ['direccion_id' => $deptLuis->id, 'nombre' => 'Luz principal',     'tipo' => 'luz',           'estado' => 'apagado'],
            ['direccion_id' => $deptLuis->id, 'nombre' => 'Ventana sala',      'tipo' => 'sensor_ventana','estado' => 'cerrado'],

            // Oficina de Luis — plan Premium
            ['direccion_id' => $ofLuis->id,   'nombre' => 'Luz sala reuniones','tipo' => 'luz',           'estado' => 'apagado'],
            ['direccion_id' => $ofLuis->id,   'nombre' => 'Luz recepción',     'tipo' => 'luz',           'estado' => 'encendido'],
            ['direccion_id' => $ofLuis->id,   'nombre' => 'Cámara entrada',    'tipo' => 'camara',        'estado' => 'activo'],
            ['direccion_id' => $ofLuis->id,   'nombre' => 'Persiana ventanal', 'tipo' => 'persiana',      'estado' => 'abierto'],
        ];

        foreach ($dispositivos as $disp) {
            Dispositivo::firstOrCreate(
                ['direccion_id' => $disp['direccion_id'], 'nombre' => $disp['nombre']],
                $disp
            );
        }
    }
}
