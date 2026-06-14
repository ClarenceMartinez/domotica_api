<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDispositivoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100'],
            'tipo'   => ['required', 'in:luz,persiana,sensor_puerta,sensor_ventana,camara,calefaccion'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'tipo'   => 'tipo de dispositivo',
        ];
    }
}
