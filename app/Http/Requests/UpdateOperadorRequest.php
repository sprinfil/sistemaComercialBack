<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            "nombre"=>"required|string|max:55",
            "apellido_paterno"=>"required|string|max:55",
            "apellido_materno"=>"required|string|max:55",
            "fecha_nacimiento"=>"required|date|max:55",
        ];
    }
}
