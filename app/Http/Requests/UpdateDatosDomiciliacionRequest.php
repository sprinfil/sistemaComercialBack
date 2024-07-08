<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDatosDomiciliacionRequest extends FormRequest
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
            "id_toma"=>"required|int",
            "numero_cuenta"=>"required|string",
            "fecha_vencimiento"=>"required|string",
            "tipo_tarjeta"=>"required|string",
            "limite_cobro"=>"required|numeric|regex:/^\d+(\.\d{1,2})?$/",
            "domicilio_tarjeta"=>"required|string",
        ];
    }
}
