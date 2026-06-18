<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlindReading;
use App\Models\Device;
use Illuminate\Http\Request;

class BlindController extends Controller
{
    /**
     * RPi → App: registra una lectura y actualiza el estado del dispositivo.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'device_id' => 'required|integer',
            'house_id'  => 'required|integer',
            'value'     => 'required|integer|min:0|max:100',
        ]);

        BlindReading::create([
            'device_id'  => $data['device_id'],
            'address_id' => $data['house_id'],
            'value'      => $data['value'],
        ]);

        $device = Device::find($data['device_id']);
        if ($device) {
            $device->update([
                'status'   => $data['value'] > 0 ? 'open' : 'closed',
                'settings' => array_merge($device->settings ?? [], ['position' => $data['value']]),
            ]);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * App → RPi: abre todas las persianas de una dirección.
     */
    public function openAll(int $addressId)
    {
        $blinds = Device::where('address_id', $addressId)
            ->where('type', 'blind')
            ->get();

        foreach ($blinds as $blind) {
            $blind->update([
                'status'   => 'open',
                'settings' => array_merge($blind->settings ?? [], ['position' => 100]),
            ]);
        }

        return response()->json(['ok' => true, 'updated' => $blinds->count()]);
    }
}
