<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConceptoAplicableRequest extends FormRequest
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
            "id" => "required|integer|gt:0",

            "concepto_aplicable"=>"required|array",
            "concepto_aplicable.*.rango_minimo"=>"required|numeric|regex:/^\d+(\.\d{1,2})?$/",
            "concepto_aplicable.*.rango_maximo"=>"required|numeric|regex:/^\d+(\.\d{1,2})?$/"
        ];
    }
}
