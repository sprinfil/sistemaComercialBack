<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConceptoCatalogoRequest extends FormRequest
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
            "nombre"=>"required|string|max:55|unique:concepto_catalogos,nombre,".$this->id,
            "descripcion"=>"nullable|string",
            "estado"=>"nullable|string|max:10|
            in:activo,inactivo",
            "prioridad_abono"=>"required|int",
            "genera_iva"=>"required|boolean",
            "abonable"=>"required|boolean",
            "tarifa_fija"=>"required|boolean",
            "cargo_directo"=>"required|boolean",
            "genera_orden"=>"nullable|int",
            "tarifas.*"=>"nullable|array"
        ];
    }
}
