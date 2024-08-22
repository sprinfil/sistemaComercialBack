<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrdenTrabajoConfRequest extends FormRequest
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
            "id_orden_trabajo_catalogo" => "required|exists:orden_trabajo_catalogos,id",
            "id_concepto_catalogo" => "required|exists:concepto_catalogos,id",
            "accion"=>"required|in:generar,modificar,quitar",
            "momento"=>"required|in:generar,asignar,concluir",
            "atributo"=>"required|string",
            "valor"=>"required|string",
        ];
    }
}
