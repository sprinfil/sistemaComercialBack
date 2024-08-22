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
            "id_operador" => "required|integer|gt:0",    
            "id_caja_catalogo" => "required|integer|gt:0",
            "fondo_inicial" => "required|numeric|regex:/^\d+(\.\d{2})$/",
            //"fondo_final" => "nullable|numeric|regex:/^\d+(\.\d{2})$/",//esto no debe estar lleno en la apertura
            "fecha_apertura" => "required|date_format:Y-m-d H:i:s",
           // "fecha_cierre" => "nullable|date_format:Y-m-d H:i:s" //esto no debe estar lleno en la apertura
        ];
    }


     /**
         * request 1 este se usa en el store, se crea un registro en cajas y en fondo de caja
         * caja_data
         * fondo_data
         * 
         * request 2 este se utiliza en el cierre, se actualiza la fecha de cierre en la caja y se crea un registro en el cierre de caja
         * caja_data
         * corte_data
         */
}
