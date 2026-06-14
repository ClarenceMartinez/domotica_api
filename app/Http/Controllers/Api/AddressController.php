<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;

class AddressController extends Controller
{
    private const TYPE_GROUP = [
        'light'         => 'lights',
        'blind'         => 'blinds',
        'door_sensor'   => 'sensors',
        'window_sensor' => 'sensors',
        'camera'        => 'cameras',
        'heating'       => 'heating',
    ];

    private const ACTIVE_STATUSES = ['on', 'open', 'active'];

    public function state(int $address_id)
    {
        $address = Address::with('devices')->findOrFail($address_id);

        $commands = [];

        foreach ($address->devices as $device) {
            $group  = self::TYPE_GROUP[$device->type] ?? $device->type;
            $active = in_array($device->status, self::ACTIVE_STATUSES);

            if ($device->type === 'heating') {
                $value = ['status' => $active];
                if (isset($device->settings['target_temperature'])) {
                    $value['target_temperature'] = $device->settings['target_temperature'];
                }
            } elseif ($device->type === 'blind') {
                $value = $device->settings['position'] ?? ($active ? 100 : 0);
            } else {
                $value = $active;
            }

            $commands[$group][$device->name] = $value;
        }

        return response()->json([
            'success'    => true,
            'address_id' => $address->id,
            'commands'   => $commands,
            'mode'       => 'manual',
        ]);
    }
}
