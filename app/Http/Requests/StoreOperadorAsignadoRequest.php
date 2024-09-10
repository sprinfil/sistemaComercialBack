<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOperadorAsignadoRequest extends FormRequest
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
            "operadores_asignados"=>"required|array",
            "operadores_asignados.*"=>"required|array:id,id_operador,id_caja_catalogo",
            "id.*."=>"sometimes|numeric",
            "id_operador.*."=>"required|integer|gt:0",
            "id_caja_catalogo.*."=>"required|integer|gt:0",
        ];
    }
}