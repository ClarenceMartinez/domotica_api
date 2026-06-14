<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommandLog extends Model
{
    protected $table = 'command_logs';

    protected $fillable = ['device_id', 'action', 'successful', 'rpi_response'];

    protected $casts = ['successful' => 'boolean'];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
