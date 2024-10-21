<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConvenioCatalogoRequest extends FormRequest
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
            "nombre"=>"required|string|max:55",
            "descripcion"=>"nullable|string",
            "estado"=>"nullable|string|max:10|
            in:activo,inactivo",
            "pago_inicial"=>"nullable|numeric|regex:/^\d+(\.\d{1,2})?$/|min:0|max:100",
            "tipo_cancelacion"=>"nullable|string|
            in:manual,automatica",
        ];
    }
}
