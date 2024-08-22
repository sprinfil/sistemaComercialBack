<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCargoDirectoRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cambia esto si necesitas control de acceso
    }

    public function rules()
    {
        return [
            'cargos' => 'required|array|min:1',
            'cargos.*.id_concepto' => 'required|integer|exists:concepto_catalogos,id',
            'cargos.*.monto' => 'required|numeric|min:0',
            'id_dueno' => 'required|integer',  // Cambia 'duenos' por la tabla correcta
            'modelo_dueno' => 'required|string',
            'id_origen' => 'required|integer',  // Cambia 'origenes' por la tabla correcta
            'modelo_origen' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'cargos.required' => 'Debe proporcionar al menos un cargo.',
            'cargos.*.id_concepto.required' => 'El campo id_concepto es obligatorio.',
            'cargos.*.id_concepto.exists' => 'El concepto proporcionado no existe.',
            'cargos.*.monto.required' => 'El campo monto es obligatorio.',
            'cargos.*.monto.min' => 'El monto debe ser un valor positivo.',
            'id_dueno.required' => 'El campo id_dueno es obligatorio.',
            'id_dueno.exists' => 'El dueño proporcionado no existe.',
            'modelo_dueno.required' => 'El campo modelo_dueno es obligatorio.',
            'id_origen.required' => 'El campo id_origen es obligatorio.',
            'id_origen.exists' => 'El origen proporcionado no existe.',
            'modelo_origen.required' => 'El campo modelo_origen es obligatorio.',
        ];
    }
}
