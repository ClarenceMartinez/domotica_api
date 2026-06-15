<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeatingReading;
use Illuminate\Http\Request;

class HeatingController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'house_id'           => 'required|integer',
            'temperature_c'      => 'required|numeric',
            'humidity'           => 'required|numeric',
            'heating_status'     => 'required|string',
            'heating_mode'       => 'required|string',
            'target_temperature' => 'nullable|numeric',
            'device'             => 'nullable|string',
        ]);

        HeatingReading::updateOrCreate(
            ['address_id' => $data['house_id']],
            [
                'temperature_c'      => $data['temperature_c'],
                'humidity'           => $data['humidity'],
                'heating_status'     => $data['heating_status'],
                'heating_mode'       => $data['heating_mode'],
                'target_temperature' => $data['target_temperature'] ?? null,
                'device'             => $data['device'] ?? null,
                'read_at'            => now(),
            ]
        );

        return response()->json(['ok' => true]);
    }

    public function calibrate(Request $request, int $addressId)
    {
        $data = $request->validate([
            'target_temperature' => 'required|numeric|min:32|max:122',
            'heating_mode'       => 'required|in:manual,auto',
            'heating_status'     => 'required|in:on,off',
        ]);

        $reading = HeatingReading::where('address_id', $addressId)->first();

        if ($reading) {
            $reading->update([
                'target_temperature' => $data['target_temperature'],
                'heating_mode'       => $data['heating_mode'],
                'heating_status'     => $data['heating_status'],
            ]);
        } else {
            $reading = HeatingReading::create([
                'address_id'         => $addressId,
                'temperature_c'      => 0,
                'humidity'           => 0,
                'heating_status'     => $data['heating_status'],
                'heating_mode'       => $data['heating_mode'],
                'target_temperature' => $data['target_temperature'],
                'read_at'            => now(),
            ]);
        }

        // TODO: forward calibration to Pico W when its endpoint is available
        // Http::post("http://{$picoIp}/calibrate", $data);

        return response()->json([
            'target_temperature' => $reading->target_temperature,
            'heating_mode'       => $reading->heating_mode,
            'heating_status'     => $reading->heating_status,
        ]);
    }

    public function show(int $addressId)
    {
        $reading = HeatingReading::where('address_id', $addressId)->first();

        if (! $reading) {
            return response()->json(null);
        }

        return response()->json([
            'temperature_c'      => $reading->temperature_c,
            'humidity'           => $reading->humidity,
            'heating_status'     => $reading->heating_status,
            'heating_mode'       => $reading->heating_mode,
            'target_temperature' => $reading->target_temperature,
            'device'             => $reading->device,
            'read_at'            => $reading->read_at,
        ]);
    }
}
