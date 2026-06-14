<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDireccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plan_id'  => ['required', 'exists:planes,id'],
            'alias'    => ['required', 'string', 'max:80'],
            'calle'    => ['required', 'string', 'max:200'],
            'latitud'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    public function attributes(): array
    {
        return [
            'plan_id'  => 'plan',
            'alias'    => 'alias',
            'calle'    => 'dirección',
            'latitud'  => 'latitud',
            'longitud' => 'longitud',
        ];
    }
}
