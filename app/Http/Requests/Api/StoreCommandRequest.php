<?php

namespace App\Http\Requests\Api;

use App\Models\Device;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommandRequest extends FormRequest
{
    private const ACTIONS_PER_TYPE = [
        'light'         => ['turn_on', 'turn_off'],
        'blind'         => ['open', 'close', 'set_position'],
        'heating'       => ['turn_on', 'turn_off', 'set_temperature'],
        'door_sensor'   => [],
        'window_sensor' => [],
        'camera'        => [],
    ];

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'device_id'                   => ['required', 'integer', 'exists:devices,id'],
            'action'                      => ['required', 'string', 'in:turn_on,turn_off,open,close,activate,deactivate,set_temperature,set_position'],
            'settings'                    => ['sometimes', 'array'],
            'settings.target_temperature' => ['sometimes', 'numeric', 'min:32', 'max:122'],
            'settings.position'           => ['sometimes', 'integer', 'min:0', 'max:100'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $device  = Device::find($this->device_id);
            $allowed = self::ACTIONS_PER_TYPE[$device->type] ?? [];

            if (empty($allowed)) {
                $validator->errors()->add('action', "Devices of type \"{$device->type}\" do not accept commands.");
                return;
            }

            if (!in_array($this->action, $allowed)) {
                $validator->errors()->add(
                    'action',
                    "Action \"{$this->action}\" is not valid for devices of type \"{$device->type}\". Allowed: " . implode(', ', $allowed) . '.'
                );
                return;
            }

            if ($this->action === 'set_temperature' && !isset($this->settings['target_temperature'])) {
                $validator->errors()->add('settings.target_temperature', 'target_temperature is required for set_temperature action.');
            }

            if ($this->action === 'set_position' && !isset($this->settings['position'])) {
                $validator->errors()->add('settings.position', 'position is required for set_position action.');
            }
        });
    }
}
