<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Client;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $ana   = Client::where('email', 'ana.garcia@mail.com')->first();
        $luis  = Client::where('email', 'luis.martinez@mail.com')->first();
        $sofia = Client::where('email', 'sofia.lopez@mail.com')->first();

        $basic    = Plan::where('name', 'Basic')->first();
        $standard = Plan::where('name', 'Standard')->first();
        $premium  = Plan::where('name', 'Premium')->first();

        $addresses = [
            [
                'client_id' => $ana->id,
                'plan_id'   => $standard->id,
                'alias'     => 'Home',
                'street'    => 'Av. Corrientes 1234, Buenos Aires',
                'latitude'  => -34.6037,
                'longitude' => -58.3816,
            ],
            [
                'client_id' => $luis->id,
                'plan_id'   => $basic->id,
                'alias'     => 'Apartment',
                'street'    => 'Av. Santa Fe 890, Buenos Aires',
                'latitude'  => -34.5958,
                'longitude' => -58.3940,
            ],
            [
                'client_id' => $luis->id,
                'plan_id'   => $premium->id,
                'alias'     => 'Office',
                'street'    => 'Maipú 255, Buenos Aires',
                'latitude'  => -34.6025,
                'longitude' => -58.3757,
            ],
            [
                'client_id' => $sofia->id,
                'plan_id'   => $basic->id,
                'alias'     => 'Home',
                'street'    => 'Belgrano 450, Córdoba',
                'latitude'  => -31.4135,
                'longitude' => -64.1811,
            ],
        ];

        foreach ($addresses as $addr) {
            Address::firstOrCreate(
                ['client_id' => $addr['client_id'], 'alias' => $addr['alias']],
                $addr
            );
        }
    }
}
