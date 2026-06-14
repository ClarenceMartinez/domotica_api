<?php

namespace App\Http\Requests\Api;

use App\Models\Dispositivo;
use Illuminate\Foundation\Http\FormRequest;

class StoreComandoRequest extends FormRequest
{
    private const ACCIONES_POR_TIPO = [
        'luz'            => ['encender', 'apagar'],
        'persiana'       => ['abrir', 'cerrar'],
        'calefaccion'    => ['encender', 'apagar'],
        'sensor_puerta'  => [],
        'sensor_ventana' => [],
        'camara'         => [],
    ];

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

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $dispositivo = Dispositivo::find($this->dispositivo_id);
            $permitidas  = self::ACCIONES_POR_TIPO[$dispositivo->tipo] ?? [];

            if (empty($permitidas)) {
                $validator->errors()->add('accion', "Los dispositivos de tipo \"{$dispositivo->tipo}\" no aceptan comandos.");
                return;
            }

            if (!in_array($this->accion, $permitidas)) {
                $validator->errors()->add(
                    'accion',
                    "La acción \"{$this->accion}\" no es válida para dispositivos de tipo \"{$dispositivo->tipo}\". Acciones permitidas: " . implode(', ', $permitidas) . '.'
                );
            }
        });
    }
}
