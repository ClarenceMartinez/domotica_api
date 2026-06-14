<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $planes = [
            ['nombre' => 'Básico',   'descripcion' => 'Ideal para un ambiente.',          'max_dispositivos' => 5],
            ['nombre' => 'Estándar', 'descripcion' => 'Para hogares completos.',           'max_dispositivos' => 15],
            ['nombre' => 'Premium',  'descripcion' => 'Sin límites para grandes espacios.','max_dispositivos' => 50],
        ];

        foreach ($planes as $plan) {
            Plan::firstOrCreate(['nombre' => $plan['nombre']], $plan);
        }
    }
}
