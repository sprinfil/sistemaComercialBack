<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCargoRequest extends FormRequest
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
            "id_origen"=>"required|integer|max:55",
            "modelo_origen"=>"required|string|max:55",
            "id_dueño"=>"required|integer|max:55",
            "modelo_dueño"=>"required|string|max:55",
            "monto"=>"required|numeric|regex:/^\d+(\.\d{1,2})?$/",
            "estado"=>"required|string|max:55",
            "fecha_cargo"=>"required|date|max:55",
            "fecha_liquidacion"=>"required|date|max:55",
        ];
    }
}
