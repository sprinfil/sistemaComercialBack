<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePagoRequest extends FormRequest
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
            "id_caja"=>"required|integer",
            "id_dueno"=>"required|integer",
            "modelo_dueno"=>"required|string|max:55",
            "total_pagado"=>"required|numeric|regex:/^\d+(\.\d{1,2})?$/",
            "forma_pago"=>"required|string|max:55|
               in:efectivo,tarjeta_debito,tarjeta_credito,documento",
            "fecha_pago"=>"required|date",
            "estado"=>"nullable|string|max:55",
            "timbrado"=>"nullable|string|max:55",
            "cargos.*"=>"nullable|array",
            "bonificaciones.*"=>"nullable|array"
        ];
    }
}
