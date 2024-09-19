<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConvenioRequest extends FormRequest
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
          
            "id_convenio_catalogo" => "required|integer|gt:0",
            "id_modelo" => "required|integer|gt:0",
            "modelo_origen" => "required|string|
            in:toma,usuario",
            "cantidad_letras" => "required|integer|min:1",
            "comentario" => "nullable|string|max:50",
          
            "cargos_conveniados"=>"required|array",
            "cargos_conveniados.*.id"=>"required|integer|gt:0",
            "cargos_conveniados.*.porcentaje_conveniado"=>"required|numeric|gt:0"
        ];
    }
}
