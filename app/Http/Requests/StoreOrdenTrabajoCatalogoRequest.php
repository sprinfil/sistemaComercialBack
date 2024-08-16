<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdenTrabajoCatalogoRequest extends FormRequest
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
            "orden_trabajo_catalogo.id_concepto_catalogo" => "sometimes|exists:concepto_catalogos,id",
            "orden_trabajo_catalogo.nombre" => "required|string|unique:orden_trabajo_catalogos,nombre",
            "orden_trabajo_catalogo.descripcion" => "required|string",
            "orden_trabajo_catalogo.vigencias" => "required|integer",
            "orden_trabajo_catalogo.momento_cargo" => "required|in:generar,asignar,concluir,No genera",
            "orden_trabajo_catalogo.genera_masiva" => "required|boolean",
            //acciones
            "orden_trabajo_accion" => "nullable|array",
            "orden_trabajo_accion.*" => "sometimes|array:id_orden_trabajo_catalogo,id_concepto_catalogo,accion,modelo,campo,opcional,id_orden_trabajo_acc_encadena,id_orden_trabajo_acc_alterna",
            "orden_trabajo_accion.*.id_orden_trabajo_catalogo" => "sometimes|exists:orden_trabajo_catalogos,id",
            "orden_trabajo_accion.*.id_concepto_catalogo" => "sometimes|exists:concepto_catalogos,id",
            "orden_trabajo_accion.*.accion"=>"sometimes|in:registrar,modificar,quitar",
            "orden_trabajo_accion.*.modelo"=>"sometimes|in:toma,medidor,contrato,lectura,usuario",
            "orden_trabajo_accion.*.campo"=>"sometimes|string",
            "orden_trabajo_accion.*.opcional"=>"sometimes|boolean",
             "orden_trabajo_accion.*.id_orden_trabajo_acc_encadena"=>"sometimes|string",
            "orden_trabajo_accion.*.id_orden_trabajo_acc_alterna"=>"sometimes|string",
            //cargos
            "orden_trabajo_cargos" => "nullable|array",
            "orden_trabajo_cargos.*" => "sometimes|array:id_concepto_catalogo",
            "orden_trabajo_cargos.*.id_concepto_catalogo" => "sometimes|exists:concepto_catalogos,id",
            //encadenados
            "orden_trabajo_encadenadas" => "nullable|array",
            "orden_trabajo_encadenadas.*" => "sometimes|array:id_OT_Catalogo_encadenada",
            "orden_trabajo_encadenadas.*.id_OT_Catalogo_encadenada" => "sometimes|distinct|exists:orden_trabajo_catalogos,id",
        ];
    }
}
