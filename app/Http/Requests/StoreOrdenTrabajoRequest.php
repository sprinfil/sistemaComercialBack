<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdenTrabajoRequest extends FormRequest
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
            "ordenes_trabajo"=> "required|array",
            "ordenes_trabajo.*"=> "required|array:id_toma,id_empleado_asigno,id_empleado_encargado,id_orden_trabajo_catalogo,posicion_OT",
            "id_toma.*." => "required|exists:toma,id",
            "id_empleado_asigno.*." => "required|exists:operadores,id",
            "id_empleado_encargado.*." => "sometimes|exists:operadores,id",
            "id_orden_trabajo_catalogo.*." => "required|exists:orden_trabajo_catalogos,id",
            "posicion_OT.*." => "sometimes|point",
            /*
            "modelos"=> "required|array:toma,medidores,contratos,usuarios",
            
            "toma"=> "sometimes|array",
            "toma.*"=> "sometimes|array:estatus,c_agua,c_alc,c_san,tipo_servicio,tipo_contratacion",

            "medidores"=> "sometimes|array",
            "medidores.*"=> "sometimes|array:estatus,c_agua,c_alc,c_san,tipo_servicio,tipo_contratacion",

            "usuarios"=> "sometimes|array",
            "usuarios.*"=> "sometimes|array:estatus,c_agua,c_alc,c_san,tipo_servicio,tipo_contratacion",

            "contratos"=> "sometimes|array",
            "contratos.*"=> "sometimes|array:estatus,c_agua,c_alc,c_san,tipo_servicio,tipo_contratacion",
            */
        ];
    }
}
