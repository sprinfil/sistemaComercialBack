<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCajasRequest extends FormRequest
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

            "caja_data"=>"required|array",   
            "caja_data.*.id_caja_catalogo" => "required|integer|gt:0",
            "caja_data.*.fondo_final" => "required|numeric|regex:/^\d+(\.\d{2})$/",
          

            "corte_data"=>"required|array",
            "corte_data.*.cantidad_centavo_10"=>"required|integer|min:0",
            "corte_data.*.cantidad_centavo_20"=>"required|integer|min:0",
            "corte_data.*.cantidad_centavo_50"=>"required|integer|min:0",
            "corte_data.*.cantidad_moneda_1"=>"required|integer|min:0",
            "corte_data.*.cantidad_moneda_2"=>"required|integer|min:0",
            "corte_data.*.cantidad_moneda_5"=>"required|integer|min:0",
            "corte_data.*.cantidad_moneda_10"=>"required|integer|min:0",
            "corte_data.*.cantidad_moneda_20"=>"required|integer|min:0",
            "corte_data.*.cantidad_billete_20"=>"required|integer|min:0",
            "corte_data.*.cantidad_billete_50"=>"required|integer|min:0",
            "corte_data.*.cantidad_billete_100"=>"required|integer|min:0",
            "corte_data.*.cantidad_billete_200"=>"required|integer|min:0",
            "corte_data.*.cantidad_billete_500"=>"required|integer|min:0",
            "corte_data.*.cantidad_billete_1000"=>"required|integer|min:0",

            "corte_data.*.total_efectivo_real"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            "corte_data.*.total_tarjetas_credito_real"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            "corte_data.*.total_tarjetas_debito_real"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            "corte_data.*.total_cheques_real"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            "corte_data.*.total_transferencias_real"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            "corte_data.*.total_documentos_real"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
             "corte_data.*.total_real"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
             "corte_data.*.descripcion"=>"nullable|string|max:50",
           
        ];
    }
}
