<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSecuenciaRequest extends FormRequest
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
            "Secuencia"=>"required",
            "Secuencia.id"=>"nullable",
            "Secuencia.id_empleado"=>"nullable",
            "Secuencia.id_libro"=>"required",
            "Secuencia.tipo_secuencia"=>"required|in:padre,personalizada",
            ///Secuencia ordenes
            "Secuencia_ordenes"=>"sometimes",
            "Secuencia_ordenes.*"=>"sometimes|array",
            "Secuencia_ordenes.*.id_secuencia"=>"sometimes", //|exists:secuencias,id
            "Secuencia_ordenes.*.id_toma"=>"sometimes|exists:toma,id",
            "Secuencia_ordenes.*.numero_secuencia"=>"sometimes|integer",
        ];
    }
}
