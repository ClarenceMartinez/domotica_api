<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutletReading extends Model
{
    protected $fillable = [
        'address_id',
        'device_id',
        'outlet_name',
        'outlet_status',
        'power_watts',
        'minutes_on',
        'minutes_over_limit',
        'max_safe_minutes',
        'appliance_type',
        'energy_status',
        'risk_level',
        'message',
    ];

    protected $casts = [
        'power_watts'        => 'float',
        'minutes_on'         => 'integer',
        'minutes_over_limit' => 'integer',
        'max_safe_minutes'   => 'integer',
    ];
}
