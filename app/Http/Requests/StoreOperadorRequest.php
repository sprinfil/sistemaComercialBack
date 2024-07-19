<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreOperadorRequest extends FormRequest
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
            "name" => "required",
            "email"=>"nullable|email|unique:users,email",
            "password"=>[
                "required",
                "confirmed",
                Password::min(3)
                //->letters()
                //->symbols()
            ],
            "codigo_empleado"=>"required|string|max:55|unique:operadores,codigo_empleado",
            "nombre"=>"required|string|max:55",
            "apellido_paterno"=>"required|string|max:55",
            "apellido_materno"=>"required|string|max:55",
            "CURP"=>"required|string|max:18|unique:operadores,CURP",
            "fecha_nacimiento"=>"required|date|max:55",
        ];
    }
}
