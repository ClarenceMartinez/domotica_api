<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = ['nombre', 'email', 'telefono'];

    public function direcciones(): HasMany
    {
        return $this->hasMany(Direccion::class);
    }
}
