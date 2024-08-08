<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCargoDirectoRequest extends FormRequest
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
            "id_cargo"=>"required|integer",
            /*
            "id_origen"=>"required|integer",
            "modelo_origen"=>"required|string|max:55",
            "id_dueno"=>"required|integer",
            "modelo_dueno"=>"required|string|max:55",
            "monto"=>"required|numeric|regex:/^\d+(\.\d{1,2})?$/",
            "estado"=>"required|string|max:55",
            "fecha_cargo"=>"required|date|max:55",
            "fecha_liquidacion"=>"required|date|max:55", */
        ];
    }
}
