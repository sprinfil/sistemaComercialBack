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
            "contrato"=>"required|array",
            'contrato.id_usuario' => 'required|exists:usuarios,id',
            'contrato.id_toma' => 'sometimes|exists:toma,id',
            'contrato.estatus' => 'sometimes|in:pendiente de inspeccion,contrato no factible,inspeccionado,pendiente de pago,contratado,terminado',
            'contrato.nombre_contrato' => 'sometimes|string',
            'contrato.clave_catastral' => 'nullable|string|min:9|max:12',
            'contrato.tipo_toma' => 'sometimes|string',
            'contrato.servicio_contratados.*' => 'sometimes|in:agua,alcantarillado y saneamiento',
            'contrato.colonia' => 'sometimes|string',
            'contrato.calle' => 'sometimes|string',
            'contrato.municipio' => 'sometimes|string',
            'contrato.localidad' => 'sometimes|string',
            'contrato.entre_calle1' => 'nullable|string',
            'contrato.entre_calle2' => 'nullable|string',
            'contrato.domicilio' => 'sometimes|string',
            'contrato.diametro_de_la_toma' => 'sometimes|string',
            'contrato.codigo_postal' => 'sometimes|string|numeric',
            'contrato.coordenada' => 'nullable|string',

            "toma"=>"sometimes|array",
            "toma.id_giro_comercial"=>'sometimes|exists:giro_comercial_catalogos,id',
            "toma.calle"=>'sometimes|string',
            "toma.colonia"=>'sometimes|string',
            "toma.localidad"=>'sometimes|string',
            "toma.calle_notificaciones"=>'sometimes|string',
            "toma.tipo_servicio"=> 'sometimes|in:lectura,promedio',
            "toma.tipo_contratacion"=> 'sometimes|in:normal, condicionado, desarrollador',
            "toma.posicion"=>'nullable|array',
            
            "ordenes_trabajo"=> "sometimes",
            "ordenes_trabajo.id_toma" => "sometimes|exists:toma,id",
            "ordenes_trabajo.posicion_OT" => "sometimes|point",
            "ordenes_trabajo.genera_OT_encadenadas" => "sometimes|boolean",
        ];
    }
}
