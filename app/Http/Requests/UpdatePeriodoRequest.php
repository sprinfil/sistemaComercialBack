<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePeriodoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cambiar según la lógica de autorización necesaria.
    }

    public function rules(): array
    {
        return [
            'id_ruta' => 'sometimes|required|exists:rutas,id',
            'id_tarifa' => 'sometimes|required|exists:tarifas,id',
            'nombre' => 'sometimes|required|string|max:255',
            'periodo' => 'sometimes|required|date',
            'facturacion_fecha_inicio' => 'sometimes|required|date',
            'facturacion_fecha_final' => 'sometimes|required|date',
            'lectura_inicio' => 'sometimes|required|date',
            'lectura_final' => 'sometimes|required|date',
        ];
    }
}
