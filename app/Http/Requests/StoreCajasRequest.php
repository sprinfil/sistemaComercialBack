<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCajasRequest extends FormRequest
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
  
            "id_operador"=>"required|integer|gt:0",
            "id_caja"=>"required|integer|gt:0",
            "caja_data"=>"required|array",
            "caja_data.*.id_operador"=>"required|integer|gt:0",
            "caja_data.*.fecha_apertura"=>"required|date_format:Y-m-d H:i:s",
            "caja_data.*.fecha_cierre"=>"required|date_format:Y-m-d H:i:s",
            "fondo_data"=>"required|array",
            "fondo_data.*.id_caja"=>"required|integer|gt:0",
            "fondo_data.*.monto"=>"required|numeric|/^\d+(\.\d{2})$/",
            "fondo_data.*.monto_inicial"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            "fondo_data.*.monto_final"=>"required|numeric|regex:/^\d+(\.\d{2})$/",  

        ];
    }

     /**
         * request 1 este se usa en el store, se crea un registro en cajas y en fondo de caja
         * id_caja ya
         * id_operador ya
         * caja_data
         * fondo_data
         * 
         * request 2 este se utiliza en el cierre, se actualiza la fecha de cierre en la caja y se crea un registro en el cierre de caja
         * id_caja ya
         * id_operador ya
         * caja_data
         * corte_data
         */
}
