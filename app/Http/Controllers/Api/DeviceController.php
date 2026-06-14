<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeviceResource;
use App\Models\Address;
use App\Models\Device;

class DeviceController extends Controller
{
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
