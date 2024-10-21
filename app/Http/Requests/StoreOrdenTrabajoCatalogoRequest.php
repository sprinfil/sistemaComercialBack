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
            "id_orden_trabajo_catalogo"=>"sometimes|exists:concepto_catalogos,id",
            "orden_trabajo_catalogo.id_concepto_catalogo" => "sometimes|exists:concepto_catalogos,id",
            "orden_trabajo_catalogo.id" => "sometimes|exists:orden_trabajo_catalogos,id",
            "orden_trabajo_catalogo.nombre" => "sometimes|string",
            "orden_trabajo_catalogo.descripcion" => "sometimes|string",
            "orden_trabajo_catalogo.servicio" => "sometimes|in:CONSUMO DE AGUA POTABLE,SERV. ALCANTARILLADO,TRAT. Y SANEAMIENTO,OTRO",
            "orden_trabajo_catalogo.vigencias" => "sometimes|integer",
            "orden_trabajo_catalogo.momento_cargo" => "sometimes|in:generar,asignar,concluir,No genera",
            "orden_trabajo_catalogo.genera_masiva" => "sometimes|boolean",
            "orden_trabajo_catalogo.asigna_masiva" => "sometimes|boolean",
            "orden_trabajo_catalogo.cancela_masiva" => "sometimes|boolean",
            "orden_trabajo_catalogo.cierra_masiva" => "sometimes|boolean",
            "orden_trabajo_catalogo.limite_ordenes" => "sometimes|integer",
            "orden_trabajo_catalogo.publico_general" => "sometimes|boolean",
            //acciones
            "orden_trabajo_accion" => "sometimes|array",
            "orden_trabajo_accion.*" => "sometimes|array:id,id_orden_trabajo_catalogo,id_concepto_catalogo,accion,modelo,campo,valor,opcional,id_orden_trabajo_acc_encadena,id_orden_trabajo_acc_alterna",
            "orden_trabajo_accion.*.id" => "sometimes|numeric",
            "orden_trabajo_accion.*.id_orden_trabajo_catalogo" => "sometimes|exists:orden_trabajo_catalogos,id",
            "orden_trabajo_accion.*.id_concepto_catalogo" => "sometimes|exists:concepto_catalogos,id",
            "orden_trabajo_accion.*.accion"=>"sometimes|in:registrar,modificar,quitar",
            "orden_trabajo_accion.*.modelo"=>"sometimes|in:toma,medidores,contratos,lecturas,usuarios",
            "orden_trabajo_accion.*.campo"=>"sometimes|in:estatus,contrato_agua,contrato_alcantarillado,contrato_saneamiento,tipo_servicio,tipo_contratacion,",
            "orden_trabajo_accion.*.valor"=>"sometimes|in:activa,inactivo,baja temporal,baja definitiva,pendiente de instalación,en proceso,limitado,contrato no factible,inspeccionado,pendiente de pago,contratado,cancelado,lectura,promedio,normal,condicionado,desarrollador,de baja",
            "orden_trabajo_accion.*.opcional"=>"sometimes|boolean",
             "orden_trabajo_accion.*.id_orden_trabajo_acc_encadena"=>"sometimes|string",
            "orden_trabajo_accion.*.id_orden_trabajo_acc_alterna"=>"sometimes|string",
            //cargos
            "orden_trabajo_cargos" => "sometimes|array",
            "orden_trabajo_cargos.*" => "sometimes|array:id,id_orden_trabajo_catalogo,id_concepto_catalogo",
            "orden_trabajo_cargos.*.id" => "sometimes|numeric",
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
