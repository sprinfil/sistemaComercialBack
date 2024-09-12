<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContratoRequest extends FormRequest
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
            //'id_toma' => 'nullable',
            "contrato"=>"required|array",
            'contrato.id' => 'sometimes|integer',
            'contrato.id_toma' => 'sometimes|exists:toma,id',
            'contrato.id_usuario' => 'sometimes|exists:usuarios,id',
            'contrato.folio_solicitud' => 'sometimes|string|unique:contratos,folio_solicitud,'.$this->id,
            'contrato.estatus' => 'sometimes|in:pendiente de inspeccion,contrato no factible,inspeccionado,pendiente de pago,contratado,terminado,cancelado',
            'contrato.nombre_contrato' => 'sometimes|string',
            'contrato.clave_catastral' => 'nullable|string',
            'contrato.tipo_toma' => 'sometimes|string',
            'contrato.servicio_contratado' => 'sometimes|in:agua,alcantarillado y saneamiento',
            'contrato.colonia' => 'sometimes|integer',
            'contrato.calle' => 'sometimes|integer',
            'contrato.municipio' => 'sometimes|string',
            'contrato.localidad' => 'sometimes|string',
            'contrato.entre_calle1' => 'nullable|integer',
            'contrato.entre_calle2' => 'nullable|integer',
            'contrato.diametro_de_la_toma' => 'sometimes|string',
            'contrato.codigo_postal' => 'sometimes|string',
            'contrato.coordenada' => 'nullable|string',
        ];
    }
}
