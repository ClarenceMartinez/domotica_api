<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Device;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        $anaHome   = Client::where('email', 'ana.garcia@mail.com')->first()->addresses()->where('alias', 'Home')->first();
        $luisApt   = Client::where('email', 'luis.martinez@mail.com')->first()->addresses()->where('alias', 'Apartment')->first();
        $luisOffice = Client::where('email', 'luis.martinez@mail.com')->first()->addresses()->where('alias', 'Office')->first();

        $devices = [
            // Ana's Home — Standard plan
            ['address_id' => $anaHome->id,    'name' => 'Living room light', 'type' => 'light',         'status' => 'off'],
            ['address_id' => $anaHome->id,    'name' => 'Kitchen light',     'type' => 'light',         'status' => 'off'],
            ['address_id' => $anaHome->id,    'name' => 'Bedroom blind',     'type' => 'blind',         'status' => 'closed'],
            ['address_id' => $anaHome->id,    'name' => 'Front door',        'type' => 'door_sensor',   'status' => 'closed'],
            ['address_id' => $anaHome->id,    'name' => 'Heating',           'type' => 'heating',       'status' => 'off'],

            // Luis's Apartment — Basic plan
            ['address_id' => $luisApt->id,   'name' => 'Main light',        'type' => 'light',         'status' => 'off'],
            ['address_id' => $luisApt->id,   'name' => 'Living window',     'type' => 'window_sensor', 'status' => 'closed'],

            // Luis's Office — Premium plan
            ['address_id' => $luisOffice->id, 'name' => 'Meeting room light','type' => 'light',         'status' => 'off'],
            ['address_id' => $luisOffice->id, 'name' => 'Reception light',   'type' => 'light',         'status' => 'on'],
            ['address_id' => $luisOffice->id, 'name' => 'Entrance camera',   'type' => 'camera',        'status' => 'active'],
            ['address_id' => $luisOffice->id, 'name' => 'Window blind',      'type' => 'blind',         'status' => 'open'],
        ];

        foreach ($devices as $device) {
            Device::firstOrCreate(
                ['address_id' => $device['address_id'], 'name' => $device['name']],
                $device
            );
        }
    }
}
