<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if ($this->type === 'heating' && $this->relationLoaded('heatingReading') && $this->heatingReading) {
            $r = $this->heatingReading;
            return [
                'id'       => $this->id,
                'name'     => $this->name,
                'type'     => $this->type,
                'status'   => $r->heating_status,
                'settings' => [
                    'target_temperature' => $r->target_temperature,
                    'heating_mode'       => $r->heating_mode,
                ],
                'reading'  => [
                    'temperature_c' => $r->temperature_c,
                    'humidity'      => $r->humidity,
                    'read_at'       => $r->read_at,
                ],
            ];
        }

        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'type'     => $this->type,
            'status'   => $this->status,
            'settings' => $this->settings ?? [],
        ];
    }
}
