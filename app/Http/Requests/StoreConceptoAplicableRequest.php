<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConceptoAplicableRequest extends FormRequest
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
           "id_modelo" => "required|integer|gt:0",
            "modelo" => "required|string|
            in:ajuste_catalogo,descuento_catalogo,convenio_catalogo",
            "rango_minimo"=>"required|numeric|regex:/^\d+(\.\d{1,2})?$/",
            "rango_maximo"=>"required|numeric|regex:/^\d+(\.\d{1,2})?$/",
        ];
    }
}
