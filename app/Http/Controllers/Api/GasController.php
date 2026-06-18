<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommandLog;
use App\Models\Device;
use App\Models\GasReading;
use App\Models\HeatingReading;
use Illuminate\Http\Request;

class GasController extends Controller
{
    /**
     * RPi → App: registra una lectura de gas y ejecuta apagado automático si hay peligro.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'house_id'   => 'required|integer',
            'device_id'  => 'required|integer',
            'sensor'     => 'required|string',
            'gas_value'  => 'required|integer',
            'gas_status' => 'required|string|in:normal,danger',
            'message'    => 'nullable|string',
        ]);

        GasReading::create([
            'address_id' => $data['house_id'],
            'device_id'  => $data['device_id'],
            'sensor'     => $data['sensor'],
            'gas_value'  => $data['gas_value'],
            'gas_status' => $data['gas_status'],
            'message'    => $data['message'] ?? null,
        ]);

        if ($data['gas_status'] === 'danger') {
            $this->shutdownHeating($data['house_id']);
            $this->shutdownOutlets($data['house_id']);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Retorna la lectura más reciente por dispositivo para una dirección.
     */
    public function index(int $addressId)
    {
        $latestIds = GasReading::where('address_id', $addressId)
            ->selectRaw('MAX(id) as id')
            ->groupBy('device_id')
            ->pluck('id');

        $readings = GasReading::whereIn('id', $latestIds)
            ->orderBy('sensor')
            ->get();

        return response()->json($readings);
    }

    /**
     * Registra el comando de cierre de válvula principal de gas.
     */
    public function closeValve(Request $request, int $addressId)
    {
        $data = $request->validate([
            'device_id' => 'nullable|integer|exists:devices,id',
        ]);

        $deviceId = $data['device_id'] ?? GasReading::where('address_id', $addressId)
            ->orderBy('id', 'desc')
            ->value('device_id');

        if ($deviceId) {
            CommandLog::create([
                'device_id'  => $deviceId,
                'action'     => 'close_valve',
                'successful' => true,
            ]);
        }

        return response()->json(['ok' => true]);
    }

    private function shutdownHeating(int $addressId): void
    {
        HeatingReading::where('address_id', $addressId)
            ->update(['heating_status' => 'off']);

        Device::where('address_id', $addressId)
            ->where('type', 'heating')
            ->update(['status' => 'off']);
    }

    private function shutdownOutlets(int $addressId): void
    {
        Device::where('address_id', $addressId)
            ->where('type', 'smart_outlet')
            ->update(['status' => 'off']);
    }
}
