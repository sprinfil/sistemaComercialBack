<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDescuentoAsociadoRequest extends FormRequest
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
            "id_usuario"=>"required|integer",
            "id_toma"=>"required|integer",
            "id_descuento"=>"required|integer",
            "folio"=>"nullable|string",
            "evidencia"=>"nullable|string",
        ];
    }
}
