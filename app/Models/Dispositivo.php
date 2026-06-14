<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dispositivo extends Model
{
    protected $fillable = ['direccion_id', 'nombre', 'tipo', 'estado'];

    public function direccion(): BelongsTo
    {
        return $this->belongsTo(Direccion::class);
    }

    public function comandosLog(): HasMany
    {
        return $this->hasMany(ComandoLog::class);
    }
}
