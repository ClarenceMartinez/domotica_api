<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCommandRequest;
use App\Http\Resources\DeviceResource;
use App\Models\CommandLog;
use App\Models\Device;

class CommandController extends Controller
{
    public function store(StoreCommandRequest $request)
    {
        $device = Device::findOrFail($request->device_id);

        $newStatus = match($request->action) {
            'turn_on'        => 'on',
            'turn_off'       => 'off',
            'open'           => 'open',
            'close'          => 'closed',
            'activate'       => 'active',
            'deactivate'     => 'inactive',
            'set_temperature',
            'set_position'   => $device->status,
        };

        $updateData = ['status' => $newStatus];

        if ($request->has('settings')) {
            $updateData['settings'] = array_merge($device->settings ?? [], $request->settings);
        }

        $device->update($updateData);

        CommandLog::create([
            'device_id'  => $device->id,
            'action'     => $request->action,
            'successful' => true,
        ]);

        return response()->json([
            'device' => new DeviceResource($device),
            'action' => $request->action,
        ]);
    }
}
