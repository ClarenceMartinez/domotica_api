<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id'       => $this->id,
            'name'     => $this->name,
            'type'     => $this->type,
            'status'   => $this->status,
            'settings' => $this->settings ?? [],
        ];

        if ($this->type === 'heating' && $this->relationLoaded('heatingReading') && $this->heatingReading) {
            $r = $this->heatingReading;
            $data['reading'] = [
                'temperature_c'      => $r->temperature_c,
                'humidity'           => $r->humidity,
                'heating_status'     => $r->heating_status,
                'heating_mode'       => $r->heating_mode,
                'target_temperature' => $r->target_temperature,
                'read_at'            => $r->read_at,
            ];
        }

        return $data;
    }
}
