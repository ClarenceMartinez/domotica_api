<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            ['name' => 'Ana García',    'email' => 'ana.garcia@mail.com',    'phone' => '11-1111-1111'],
            ['name' => 'Luis Martínez', 'email' => 'luis.martinez@mail.com', 'phone' => '11-2222-2222'],
            ['name' => 'Sofía López',   'email' => 'sofia.lopez@mail.com',   'phone' => null],
        ];

        foreach ($clients as $client) {
            Client::firstOrCreate(['email' => $client['email']], $client);
        }
    }
}
