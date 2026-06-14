<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComandoLog extends Model
{
    protected $table = 'comandos_log';

    protected $fillable = ['dispositivo_id', 'accion', 'exitoso', 'respuesta_rpi'];

    protected $casts = ['exitoso' => 'boolean'];

    public function dispositivo(): BelongsTo
    {
        return $this->belongsTo(Dispositivo::class);
    }
}
