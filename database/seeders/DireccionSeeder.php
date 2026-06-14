<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Direccion;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class DireccionSeeder extends Seeder
{
    public function run(): void
    {
        $ana   = Cliente::where('email', 'ana.garcia@mail.com')->first();
        $luis  = Cliente::where('email', 'luis.martinez@mail.com')->first();
        $sofia = Cliente::where('email', 'sofia.lopez@mail.com')->first();

        $basico   = Plan::where('nombre', 'Básico')->first();
        $estandar = Plan::where('nombre', 'Estándar')->first();
        $premium  = Plan::where('nombre', 'Premium')->first();

        $direcciones = [
            [
                'cliente_id' => $ana->id,
                'plan_id'    => $estandar->id,
                'alias'      => 'Casa',
                'calle'      => 'Av. Corrientes 1234, Buenos Aires',
                'latitud'    => -34.6037,
                'longitud'   => -58.3816,
            ],
            [
                'cliente_id' => $luis->id,
                'plan_id'    => $basico->id,
                'alias'      => 'Departamento',
                'calle'      => 'Av. Santa Fe 890, Buenos Aires',
                'latitud'    => -34.5958,
                'longitud'   => -58.3940,
            ],
            [
                'cliente_id' => $luis->id,
                'plan_id'    => $premium->id,
                'alias'      => 'Oficina',
                'calle'      => 'Maipú 255, Buenos Aires',
                'latitud'    => -34.6025,
                'longitud'   => -58.3757,
            ],
            [
                'cliente_id' => $sofia->id,
                'plan_id'    => $basico->id,
                'alias'      => 'Casa',
                'calle'      => 'Belgrano 450, Córdoba',
                'latitud'    => -31.4135,
                'longitud'   => -64.1811,
            ],
        ];

        foreach ($direcciones as $dir) {
            Direccion::firstOrCreate(
                ['cliente_id' => $dir['cliente_id'], 'alias' => $dir['alias']],
                $dir
            );
        }
    }
}
