<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $table = 'planes';

    protected $fillable = ['nombre', 'descripcion', 'max_dispositivos'];

    public function direcciones(): HasMany
    {
        return $this->hasMany(Direccion::class);
    }
}
