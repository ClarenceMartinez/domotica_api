<?php

namespace App\Http\Controllers\Devices;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Address;
use App\Models\Device;

class DeviceController extends Controller
{
    private function types(): array
    {
        return [
            'light'         => 'Light',
            'blind'         => 'Blind',
            'door_sensor'   => 'Door sensor',
            'window_sensor' => 'Window sensor',
            'camera'        => 'Camera',
            'heating'       => 'Heating',
        ];
    }

    public function create(Address $address)
    {
        $address->load('plan');

        $used  = $address->devices()->count();
        $limit = $address->plan?->device_limit ?? 0;

        if ($limit > 0 && $used >= $limit) {
            return redirect()->route('addresses.show', $address)
                ->with('error', "The {$address->plan->name} plan allows a maximum of {$limit} devices.");
        }

        return view('devices.create', [
            'address' => $address,
            'types'   => $this->types(),
            'used'    => $used,
            'limit'   => $limit,
        ]);
    }

    public function store(StoreDeviceRequest $request, Address $address)
    {
        $address->load('plan');

        if (($address->plan?->device_limit ?? 0) > 0 && $address->devices()->count() >= $address->plan->device_limit) {
            return redirect()->route('addresses.show', $address)
                ->with('error', 'Device limit reached for this plan.');
        }

        $initialStatus = match($request->type) {
            'blind', 'door_sensor', 'window_sensor' => 'closed',
            'camera'                                 => 'inactive',
            default                                  => 'off',
        };

        $address->devices()->create([
            ...$request->validated(),
            'status' => $initialStatus,
        ]);

        return redirect()->route('addresses.show', $address)
            ->with('success', 'Device added successfully.');
    }

    public function edit(Device $device)
    {
        return view('devices.edit', [
            'device' => $device,
            'types'  => $this->types(),
        ]);
    }

    public function update(UpdateDeviceRequest $request, Device $device)
    {
        $device->update($request->validated());

        return redirect()->route('addresses.show', $device->address_id)
            ->with('success', 'Device updated successfully.');
    }

    public function destroy(Device $device)
    {
        $addressId = $device->address_id;
        $device->delete();

        return redirect()->route('addresses.show', $addressId)
            ->with('success', 'Device deleted successfully.');
    }
}
