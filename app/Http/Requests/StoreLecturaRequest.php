<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLecturaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //'id_operador' => 'integer', // Validar que exista en la tabla `operadores`
            'id_toma' => 'required|integer', // Validar que exista en la tabla `tomas`
            //'id_periodo' => 'required', // Validar que exista en la tabla `periodos`
            //'id_origen' => 'nullable|integer', // Cambiar a `exists:tabla_origen,id` si aplica
            //'modelo_origen' => 'nullable|string', // Ajustar longitud máxima según corresponda
            'id_anomalia' => 'nullable|integer', // Puede ser opcional o requerido según lógica
            'lectura' => 'nullable|numeric', // Validar que sea numérico y mínimo 0
            'comentario' => 'nullable|string|max:500', // Comentario opcional con longitud máxima de 500 caracteres
        ];
    }
}
