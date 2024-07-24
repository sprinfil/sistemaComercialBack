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
            "nombre" => "required|string|unique:orden_trabajo_catalogos,nombre",
            "orden_trabajo_configuracion" => "sometimes|array:id_orden_trabajo_catalogo,id_concepto_catalogo,accion,momento,atributo,valor",
            "orden_trabajo_configuracion.id_orden_trabajo_catalogo" => "sometimes|exists:orden_trabajo_catalogos,id",
            "orden_trabajo_configuracion.id_concepto_catalogo" => "sometimes|exists:concepto_catalogos,id",
            "orden_trabajo_configuracion.accion"=>"sometimes|in:generar,modificar,quitar",
            "orden_trabajo_configuracion.momento"=>"sometimes|in:generar,asignar,concluir",
            "orden_trabajo_configuracion.atributo"=>"sometimes|string",
            "orden_trabajo_configuracion.valor"=>"sometimes|string",
        ];
    }
}
