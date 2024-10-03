<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConstanciaRequest extends FormRequest
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
            "id_catalogo_constancia" => "required|integer|gt:0",
            "estado" => "required|string|
            in:pendiente,pagado,cancelado",
            "id_operador" => "required|integer|gt:0",
            "id_dueno" => "required|integer|gt:0",
            "modelo_dueno" => "required|string|
            in:toma,usuario",
        ];
    }
}
