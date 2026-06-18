<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GasReading extends Model
{
    protected $fillable = [
        'address_id',
        'device_id',
        'sensor',
        'gas_value',
        'gas_status',
        'message',
    ];

    protected $casts = [
        'gas_value' => 'integer',
    ];
}
