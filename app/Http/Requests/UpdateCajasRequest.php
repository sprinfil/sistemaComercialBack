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
            "caja_data.*.id_operador" => "required|integer|gt:0",    
            "caja_data.*.id_caja_catalogo" => "required|integer|gt:0",
          //  "caja_data.*.fondo_inicial" => "nullable|numeric|regex:/^\d+(\.\d{2})$/",//esto no debe estar lleno en el cierre
            "caja_data.*.fondo_final" => "required|numeric|regex:/^\d+(\.\d{2})$/",
           // "caja_data.*.fecha_apertura" => "nullable|date_format:Y-m-d H:i:s", //esto no debe estar lleno en el cierre
            "caja_data.*.fecha_cierre" => "required|date_format:Y-m-d H:i:s", 

            "corte_data"=>"required|array",
            //"corte_data.*.id_caja"=>"required|integer|gt:0",
            "corte_data.*.id_operador"=>"required|integer|gt:0",

            "corte_data.*.cantidad_centavo_10"=>"required|integer|gt:0",
            "corte_data.*.cantidad_centavo_20"=>"required|integer|gt:0",
            "corte_data.*.cantidad_centavo_50"=>"required|integer|gt:0",
            "corte_data.*.cantidad_moneda_1"=>"required|integer|gt:0",
            "corte_data.*.cantidad_moneda_2"=>"required|integer|gt:0",
            "corte_data.*.cantidad_moneda_5"=>"required|integer|gt:0",
            "corte_data.*.cantidad_moneda_10"=>"required|integer|gt:0",
            "corte_data.*.cantidad_moneda_20"=>"required|integer|gt:0",
            "corte_data.*.cantidad_billete_20"=>"required|integer|gt:0",
            "corte_data.*.cantidad_billete_50"=>"required|integer|gt:0",
            "corte_data.*.cantidad_billete_100"=>"required|integer|gt:0",
            "corte_data.*.cantidad_billete_200"=>"required|integer|gt:0",
            "corte_data.*.cantidad_billete_500"=>"required|integer|gt:0",
            "corte_data.*.cantidad_billete_1000"=>"required|integer|gt:0",

            // "corte_data.*.estatus"=>"required|string|in:aprobado,rechazado,pendiente",
            //"corte_data.*.total_efectivo_registrado"=>"required|numeric|regex:/^\d+(\.\d{2})$/",//Este lo calcula el sistema
            "corte_data.*.total_efectivo_real"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            //"corte_data.*.total_tarjetas_registrado"=>"required|numeric|regex:/^\d+(\.\d{2})$/",//Este lo calcula el sistema
            "corte_data.*.total_tarjetas_real"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            //"corte_data.*.total_cheques_registrado"=>"required|numeric|regex:/^\d+(\.\d{2})$/",//Este lo calcula el sistema
            "corte_data.*.total_cheques_real"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
             //"corte_data.*.total_registrado"=>"required|numeric|regex:/^\d+(\.\d{2})$/", //Este lo calcula el sistema
             "corte_data.*.total_real"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            //"corte_data.*.discrepancia"=>"required|string|in:si,no",//Este lo calcula el sistema
            //"corte_data.*.discrepancia_monto"=>"required|numeric|regex:/^\d+(\.\d{2})$/",//Este lo calcula el sistema
            "corte_data.*.fecha_corte"=>"required|date_format:Y-m-d H:i:s",
        ];
    }
}
