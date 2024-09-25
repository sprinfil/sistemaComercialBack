<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConsumoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cambiar según la lógica de autorización necesaria.
    }

    public function rules(): array
    {
        return [
            'id_toma' => 'sometimes|required|exists:tomas,id',
            'id_periodo' => 'sometimes|required|exists:periodos,id',
            'id_lectura_anterior' => 'nullable|exists:lecturas,id',
            'id_lectura_actual' => 'nullable|exists:lecturas,id',
            'tipo' => 'sometimes|required|in:promedio,lectura',
            'estado' => 'sometimes|required|in:activo,cancelado',
            'consumo' => 'sometimes|required|integer',
        ];
    }
}
