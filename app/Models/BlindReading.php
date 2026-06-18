<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlindReading extends Model
{
    protected $fillable = ['device_id', 'address_id', 'value'];
}
