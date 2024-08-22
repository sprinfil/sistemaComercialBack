<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdenTrabajoConfRequest extends FormRequest
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
           "orden_trabajo_accion" => "required|array",
            "orden_trabajo_accion.*" => "sometimes|array:id_orden_trabajo_catalogo,id_concepto_catalogo,accion,modelo,campo,opcional,id_orden_trabajo_acc_encadena,id_orden_trabajo_acc_alterna",
            "orden_trabajo_accion.*.id_orden_trabajo_catalogo" => "sometimes|exists:orden_trabajo_catalogos,id",
            "orden_trabajo_accion.*.id_concepto_catalogo" => "sometimes|exists:concepto_catalogos,id",
            "orden_trabajo_accion.*.accion"=>"sometimes|in:registrar,modificar,quitar",
            "orden_trabajo_accion.*.modelo"=>"sometimes|in:toma,medidor,contrato,lectura,usuario",
            "orden_trabajo_accion.*.campo"=>"sometimes|string",
            "orden_trabajo_accion.*.opcional"=>"sometimes|boolean",
             "orden_trabajo_accion.*.id_orden_trabajo_acc_encadena"=>"sometimes|string",
            "orden_trabajo_accion.*.id_orden_trabajo_acc_alterna"=>"sometimes|string",
        ];
    }
}
