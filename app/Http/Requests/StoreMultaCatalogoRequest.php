<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMultaCatalogoRequest extends FormRequest
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
            'nombre' => 'required|string',
            'descripcion' => 'required|string|unique:catalogo_multas,nombre',
            'UMAS_min' => 'required|integer|min:0',
            'UMAS_max' => 'required|integer|min:1|gte:UMAS_min',
            'estatus' => 'required|in:activo,inactivo'
            //|gte:UMAS_min
        ];
    }
}
