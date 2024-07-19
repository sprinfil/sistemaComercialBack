<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateOperadorRequest extends FormRequest
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
        $operador_id = $this->route('id_operador');
        $user_id = $this->route('id_user');

        return [
            "name" => "required|string|max:55",
            "email" => "nullable|email|unique:users,email," . $user_id,
            "password" => [
                "nullable",
                "confirmed",
                Password::min(3)
                //->letters()
                //->symbols()
            ],
            "codigo_empleado" => "required|string|max:55|unique:operadores,codigo_empleado," . $operador_id,
            "nombre" => "required|string|max:55",
            "apellido_paterno" => "required|string|max:55",
            "apellido_materno" => "required|string|max:55",
            "fecha_nacimiento" => "required|date|max:55",
        ];
    }
}
