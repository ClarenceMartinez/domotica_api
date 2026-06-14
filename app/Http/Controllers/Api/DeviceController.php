<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeviceResource;
use App\Models\Address;
use App\Models\Device;

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

        return DeviceResource::collection($devices);
    }
}
