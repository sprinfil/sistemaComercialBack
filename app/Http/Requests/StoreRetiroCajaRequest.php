<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRetiroCajaRequest extends FormRequest
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
            "id_caja_catalogo"=>"required|integer|gt:0",
            "cantidad_centavo_10"=>"required|integer|min:0",
            "cantidad_centavo_20"=>"required|integer|min:0",
            "cantidad_centavo_50"=>"required|integer|min:0",
            "cantidad_moneda_1"=>"required|integer|min:0",
            "cantidad_moneda_2"=>"required|integer|min:0",
            "cantidad_moneda_5"=>"required|integer|min:0",
            "cantidad_moneda_10"=>"required|integer|min:0",
            "cantidad_moneda_20"=>"required|integer|min:0",
            "cantidad_billete_20"=>"required|integer|min:0",
            "cantidad_billete_50"=>"required|integer|min:0",
            "cantidad_billete_100"=>"required|integer|min:0",
            "cantidad_billete_200"=>"required|integer|min:0",
            "cantidad_billete_500"=>"required|integer|min:0",
            "cantidad_billete_1000"=>"required|integer|min:0",
            "monto_total"=>"required|numeric|regex:/^\d+(\.\d{2})$/|min:0",
        ];
    }
}
