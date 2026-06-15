<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeatingReading extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'address_id',
        'temperature_c',
        'humidity',
        'heating_status',
        'heating_mode',
        'target_temperature',
        'device',
        'read_at',
    ];

    protected $casts = [
        'temperature_c'      => 'float',
        'humidity'           => 'float',
        'target_temperature' => 'float',
        'read_at'            => 'datetime',
    ];
}
