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
            "secuencia"=>"required",
            "secuencia.id"=>"nullable|integer",
            "secuencia.id_empleado"=>"nullable|exists:operadores,id",
            "secuencia.id_secuencia_padre"=>"nullable|integer",
            "secuencia.id_libro"=>"required|exists:libros,id",
            "secuencia.tipo_secuencia"=>"required|in:padre,personalizada",
            ///Secuencia ordenes
            "secuencia_ordenes"=>"sometimes",
            "secuencia_ordenes.*"=>"sometimes|array",
            "secuencia_ordenes.*.id_secuencia"=>"nullable|integer", //|exists:secuencias,id
            "secuencia_ordenes.*.id_toma"=>"required|exists:toma,id",
            "secuencia_ordenes.*.numero_secuencia"=>"required|integer",
        ];
    }
}
