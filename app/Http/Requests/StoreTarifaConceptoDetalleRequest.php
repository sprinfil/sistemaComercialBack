<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTarifaConceptoDetalleRequest extends FormRequest
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
            "id_tarifa"=> "required|int",
            "id_tipo_toma"=> "required|int",
            "id_concepto"=> "required|int",
            "monto"=> "required|regex:/^\d{1,9}(\.\d{2})?$/|gt:0",
        ];
    }
}
