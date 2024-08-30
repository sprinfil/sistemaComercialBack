<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrdenTrabajoCatalogoRequest extends FormRequest
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
            "orden_trabajo_catalogo.limite_ordenes" => "sometimes|integer",
        ];
    }
}
