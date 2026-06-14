<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreComandoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dispositivo_id' => ['required', 'integer', 'exists:dispositivos,id'],
            'accion'         => ['required', 'string', 'in:encender,apagar,abrir,cerrar,activar,desactivar'],
        ];
    }
}
