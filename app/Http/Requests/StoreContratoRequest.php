<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContratoRequest extends FormRequest
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
            'id_usuario' => 'required|exists:usuarios,id',
            'id_toma' => 'sometimes|required|exists:toma,id',
            'estatus' => 'sometimes|in:pendiente de inspeccion,contrato no factible,inspeccionado,pendiente de pago,contratado,terminado',
            'nombre_contrato' => 'sometimes|string',
            'clave_catastral' => 'nullable|string|min:9|max:12|unique:contratos,clave_catastral',
            'tipo_toma' => 'sometimes|string',
            'colonia' => 'sometimes|string',
            'calle' => 'sometimes|string',
            'municipio' => 'sometimes|string',
            'localidad' => 'sometimes|string',
            'entre_calle1' => 'nullable|string',
            'entre_calle2' => 'nullable|string',
            'domicilio' => 'sometimes|string',
            'diametro_de_la_toma' => 'sometimes|string',
            'codigo_postal' => 'sometimes|string|numeric',
            'coordenada' => 'nullable|string',
        ];
    }
}
