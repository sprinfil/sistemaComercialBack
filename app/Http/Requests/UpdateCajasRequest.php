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
            "id_operador"=>"required|integer|gt:0",
            "id_caja"=>"required|integer|gt:0",

            "caja_data"=>"required|array",
            "caja_data.*.id_operador"=>"required|integer|gt:0",
            "caja_data.*.fecha_apertura"=>"required|date_format:Y-m-d H:i:s",
            "caja_data.*.fecha_cierre"=>"required|date_format:Y-m-d H:i:s",

            "corte_data"=>"required|array",
            "corte_data.*.id_caja"=>"required|integer|gt:0",
            "corte_data.*.id_operador"=>"required|integer|gt:0",
            "corte_data.*.estatus"=>"required|string|in:aprobado,rechazado",
            "corte_data.*.total_registrado"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            "corte_data.*.total_real"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            "corte_data.*.total_efectivo_registrado"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            "corte_data.*.total_efectivo_real"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            "corte_data.*.total_tarjetas_registrado"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            "corte_data.*.total_tarjetas_real"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            "corte_data.*.total_cheques_registrado"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            "corte_data.*.total_cheques_real"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            "corte_data.*.discrepancia"=>"required|string|in:si,no",
            "corte_data.*.discrepancia_monto"=>"required|numeric|regex:/^\d+(\.\d{2})$/",
            "corte_data.*.fecha_corte"=>"required|date_format:Y-m-d H:i:s",
        ];
    }
}
