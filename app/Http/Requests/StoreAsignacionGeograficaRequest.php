<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAsignacionGeograficaRequest extends FormRequest
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
            "id_modelo"=>"required|int|gt:0",
            "modelo"=>"required|string|max:30|
               in:libro,ruta,toma",
            "latitud"=>"required|numeric",
            "longitud"=>"required|numeric",
            "estatus"=>"required|string|
               in:activo,inactivo",
        ];
    }
}
