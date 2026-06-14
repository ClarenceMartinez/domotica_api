<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    protected $table = 'devices';

    protected $fillable = ['address_id', 'name', 'type', 'status', 'settings'];

    protected $casts = ['settings' => 'array'];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function commandLogs(): HasMany
    {
        return $this->hasMany(CommandLog::class);
    }
}
