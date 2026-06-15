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

    /**
     * Get grouped device state for an address.
     *
     * Returns the full device state grouped by type, ready for the Raspberry Pi to consume.
     * The RPi polls this endpoint to know what to actuate on its GPIO pins.
     * Device names are used as keys within each group.
     *
     * @tags Addresses
     * @response array{
     *   success: bool,
     *   address_id: int,
     *   commands: array{
     *     lights?: array<string, bool>,
     *     blinds?: array<string, int>,
     *     heating?: array<string, array{status: bool, target_temperature?: float}>,
     *     sensors?: array<string, bool>,
     *     cameras?: array<string, bool>
     *   },
     *   mode: string
     * }
     */
    public function state(int $address_id)
    {
        $address = Address::with(['devices', 'heatingReading'])->findOrFail($address_id);

        $commands = [];

        foreach ($address->devices as $device) {
            $group  = self::TYPE_GROUP[$device->type] ?? $device->type;
            $active = in_array($device->status, self::ACTIVE_STATUSES);

            if ($device->type === 'heating') {
                $r     = $address->heatingReading;
                $value = [
                    'status'             => $r?->heating_status ?? ($active ? 'on' : 'off'),
                    'heating_mode'       => $r?->heating_mode,
                    'target_temperature' => $r?->target_temperature,
                ];
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
