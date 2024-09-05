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
            "ordenes_trabajo.*"=> "required|array:id,id_toma,id_empleado_genero,id_empleado_asigno,id_empleado_encargado,id_orden_trabajo_catalogo,posicion_OT",
            "id_toma.*." => "sometimes|exists:toma,id",
            "id_toma.*." => "sometimes|exists:toma,id",
            "id_empleado_genero.*." => "sometimes|exists:operadores,id",
            "id_empleado_asigno.*." => "sometimes|exists:operadores,id",
            "id_empleado_encargado.*." => "sometimes|exists:operadores,id",
            "id_orden_trabajo_catalogo.*." => "sometimes|exists:orden_trabajo_catalogos,id",
            "obervaciones.*." => "sometimes|string",
            "evidencia.*." => "sometimes|string",
            "material_utilizado.*." => "sometimes|string",
            "posicion_OT.*." => "sometimes|point",
            "genera_OT_encadenadas.*." => "sometimes|boolean",
            /*
            "modelos"=> "required|array:toma,medidores,contratos,usuarios",
            
            "toma"=> "sometimes|array",
            "toma.*"=> "sometimes|array:estatus,c_agua,c_alc,c_san,tipo_servicio,tipo_contratacion",

            "medidores"=> "sometimes|array",
            "medidores.*"=> "sometimes|array:id_toma,numero_serie,marca,diametro,tipo",

            "usuarios"=> "sometimes|array",
            "usuarios.*"=> "sometimes,

            "contratos"=> "sometimes|array",
            "contratos.*"=> "sometimes|array:estatus,tipo_toma,servicio_contratado",
            */
        ];
    }
}
