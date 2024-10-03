<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConsumoRequest extends FormRequest
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
            "id_toma"=>"required|integer",
            "id_periodo"=>"required|integer",
            "id_lectura_anterior"=>"nullable|integer",
            "id_lectura_actual"=>"nullable|integer",
            "tipo"=>"required|string",
            "estado"=>"required|string",
            "consumo"=>"required|string",
        ];
    }
}
