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
            "ordenes_trabajo.*"=> "required|array:id,id_toma,id_empleado_genero,id_empleado_asigno,id_empleado_encargado,id_orden_trabajo_catalogo,observaciones,evidencia,material_utilizado,posicion_OT,genera_OT_encadenadas",
            "ordenes_trabajo.*.id" => "sometimes|integer",
            "ordenes_trabajo.*.id_toma" => "sometimes|exists:toma,id",
            "ordenes_trabajo.*.id_empleado_genero" => "sometimes|exists:operadores,id",
            "ordenes_trabajo.*.id_empleado_asigno" => "sometimes|exists:operadores,id",
            "ordenes_trabajo.*.id_empleado_encargado" => "sometimes|exists:operadores,id",
            "ordenes_trabajo.*.id_orden_trabajo_catalogo" => "sometimes|exists:orden_trabajo_catalogos,id",
            "ordenes_trabajo.*.observaciones" => "sometimes|string",
            "ordenes_trabajo.*.evidencia" => "sometimes|string",
            "ordenes_trabajo.*.material_utilizado" => "sometimes|string",
            "ordenes_trabajo.*.posicion_OT" => "sometimes|point",
            "ordenes_trabajo.*.genera_OT_encadenadas" => "sometimes|boolean",

            "modelos"=> "sometimes|array",
            "modelos.medidores"=> "sometimes|array:numero_serie,marca,diametro,tipo",
            "medidores.numero_serie" => "sometimes|string",
            "medidores.marca" => "sometimes|string",
            "medidores.diametro" => "sometimes|string",
            "medidores.tipo" => "sometimes|string",
   
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
