<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLibroRequest extends FormRequest
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
            "id_ruta"=>"required|int|gt:0",
            "nombre"=>"required|string|max:20|unique:rutas,nombre",
            "latitud"=>"required|numeric",
            "longitud"=>"required|numeric",
        ];
    }
}
