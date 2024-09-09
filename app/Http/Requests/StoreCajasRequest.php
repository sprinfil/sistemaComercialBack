<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCajasRequest extends FormRequest
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
            "id_caja_catalogo" => "required|integer|gt:0",
            "fondo_inicial" => "required|numeric|regex:/^\d+(\.\d{2})$/",
            "estado" => "required|string|
            in:activo,inactivo",
        ];
    }

}
