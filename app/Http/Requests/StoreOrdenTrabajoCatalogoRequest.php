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
            "orden_trabajo_catalogo.id" => "sometimes|exists:orden_trabajo_catalogos,id",
            "orden_trabajo_catalogo.nombre" => "sometimes|string",
            "orden_trabajo_catalogo.descripcion" => "sometimes|string",
            "orden_trabajo_catalogo.vigencias" => "sometimes|integer",
            "orden_trabajo_catalogo.momento_cargo" => "sometimes|in:generar,asignar,concluir,No genera",
            "orden_trabajo_catalogo.genera_masiva" => "sometimes|boolean",
            //acciones
            "orden_trabajo_accion" => "sometimes|array",
            "orden_trabajo_accion.*" => "sometimes|array:id,id_orden_trabajo_catalogo,id_concepto_catalogo,accion,modelo,campo,opcional,id_orden_trabajo_acc_encadena,id_orden_trabajo_acc_alterna",
            "orden_trabajo_accion.*.id" => "sometimes|numeric",
            "orden_trabajo_accion.*.id_orden_trabajo_catalogo" => "sometimes|exists:orden_trabajo_catalogos,id",
            "orden_trabajo_accion.*.id_concepto_catalogo" => "sometimes|exists:concepto_catalogos,id",
            "orden_trabajo_accion.*.accion"=>"sometimes|in:registrar,modificar,quitar",
            "orden_trabajo_accion.*.modelo"=>"sometimes|in:toma,medidor,contrato,lectura,usuario",
            "orden_trabajo_accion.*.campo"=>"sometimes|string",
            "orden_trabajo_accion.*.opcional"=>"sometimes|boolean",
             "orden_trabajo_accion.*.id_orden_trabajo_acc_encadena"=>"sometimes|string",
            "orden_trabajo_accion.*.id_orden_trabajo_acc_alterna"=>"sometimes|string",
            //cargos
            "orden_trabajo_cargos" => "sometimes|array",
            "orden_trabajo_cargos.*" => "sometimes|array:id,id_orden_trabajo_catalogo,id_concepto_catalogo",
<<<<<<< HEAD
<<<<<<< HEAD
            "orden_trabajo_cargos.*.id" => "sometimes|numeric",
=======
            "orden_trabajo_cargos.*.id" => "sometimes|exist:orden_trabajo_cargos,id",
>>>>>>> 9a8f1f27e9707f737c2da27763bbbec5545e8aae
=======
            "orden_trabajo_cargos.*.id" => "sometimes|numeric",
>>>>>>> ad2a04e81eb956089ab9a4484331c515d83f6071
            "orden_trabajo_cargos.*.id_orden_trabajo_catalogo" => "sometimes|exists:orden_trabajo_catalogos,id",
            "orden_trabajo_cargos.*.id_concepto_catalogo" => "sometimes|exists:concepto_catalogos,id",
            //encadenados
            "orden_trabajo_encadenadas" => "sometimes|array",
            "orden_trabajo_encadenadas.*" => "sometimes|array:id,id_OT_Catalogo_padre,id_OT_Catalogo_encadenada",
            "orden_trabajo_encadenadas.*.id" => "sometimes|numeric",
            "orden_trabajo_cargos.*.id_OT_Catalogo_padre" => "sometimes|exists:orden_trabajo_catalogos,id",
            "orden_trabajo_encadenadas.*.id_OT_Catalogo_encadenada" => "sometimes|distinct|exists:orden_trabajo_catalogos,id",
        ];
    }
}
