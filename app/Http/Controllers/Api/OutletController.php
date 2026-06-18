<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\OutletReading;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    /**
     * RPi → App: registra una lectura histórica y sincroniza el estado del device.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'house_id'           => 'required|integer',
            'device_id'          => 'required|integer',
            'outlet_name'        => 'required|string',
            'outlet_status'      => 'required|string',
            'power_watts'        => 'required|numeric',
            'minutes_on'         => 'required|integer',
            'minutes_over_limit' => 'required|integer',
            'max_safe_minutes'   => 'required|integer',
            'appliance_type'     => 'nullable|string',
            'energy_status'      => 'required|string',
            'risk_level'         => 'required|string',
            'message'            => 'nullable|string',
        ]);

        OutletReading::create([
            'address_id'         => $data['house_id'],
            'device_id'          => $data['device_id'],
            'outlet_name'        => $data['outlet_name'],
            'outlet_status'      => $data['outlet_status'],
            'power_watts'        => $data['power_watts'],
            'minutes_on'         => $data['minutes_on'],
            'minutes_over_limit' => $data['minutes_over_limit'],
            'max_safe_minutes'   => $data['max_safe_minutes'],
            'appliance_type'     => $data['appliance_type'] ?? null,
            'energy_status'      => $data['energy_status'],
            'risk_level'         => $data['risk_level'],
            'message'            => $data['message'] ?? null,
        ]);

        $device = Device::find($data['device_id']);
        if ($device) {
            $device->update(['status' => $data['outlet_status'] === 'on' ? 'on' : 'off']);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Retorna la lectura más reciente por dispositivo para una dirección.
     */
    public function index(int $addressId)
    {
        $latestIds = OutletReading::where('address_id', $addressId)
            ->selectRaw('MAX(id) as id')
            ->groupBy('device_id')
            ->pluck('id');

        $readings = OutletReading::whereIn('id', $latestIds)
            ->orderBy('outlet_name')
            ->get();

        return response()->json($readings);
    }
}
