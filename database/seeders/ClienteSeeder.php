<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = [
            ['nombre' => 'Ana García',    'email' => 'ana.garcia@mail.com',    'telefono' => '11-1111-1111'],
            ['nombre' => 'Luis Martínez', 'email' => 'luis.martinez@mail.com', 'telefono' => '11-2222-2222'],
            ['nombre' => 'Sofía López',   'email' => 'sofia.lopez@mail.com',   'telefono' => null],
        ];

        foreach ($clientes as $cliente) {
            Cliente::firstOrCreate(['email' => $cliente['email']], $cliente);
        }
    }
}
