<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConstanciaCatalogoRequest extends FormRequest
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
            "id_concepto_catalogo" => "required|integer|gt:0",
            "nombre"=>"required|string|max:55",
            "descripcion"=>"nullable|string",
            "estado"=>"nullable|string|max:10|
            in:activo,inactivo",
        ];
    }
}
