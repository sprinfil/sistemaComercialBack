<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTipoTomaAplicableRequest extends FormRequest
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
            "id_modelo" => "required|integer|gt:0",

             "modelo_origen" => "required|string|
             in:ajuste_catalogo,descuento_catalogo,convenio_catalogo",

             "id_tipo_toma" => "required|integer|gt:0",
        ];
    }
}
