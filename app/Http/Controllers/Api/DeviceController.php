<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeviceResource;
use App\Models\Address;
use App\Models\Device;
use App\Models\HeatingReading;

class DeviceController extends Controller
{
    /**
     * List devices for an address.
     *
     * Returns every device registered under the given address with its current state and settings.
     * Consumed by the Vue panel.
     *
     * @tags Devices
     */
    public function index(int $address_id)
    {
        Address::findOrFail($address_id);

        $devices = Device::where('address_id', $address_id)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        if ($devices->contains('type', 'heating')) {
            $reading = HeatingReading::where('address_id', $address_id)->first();
            $devices->each(function ($device) use ($reading) {
                if ($device->type === 'heating') {
                    $device->setRelation('heatingReading', $reading);
                }
            });
        }

        return DeviceResource::collection($devices);
    }
}
