<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsuarioMoralRequest extends FormRequest
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
            'nombre' => 'required|string|max:255|alpha:ascii',
            'nombre_contacto' => 'nullable|string|max:255|alpha:ascii',
            'telefono' => 'required|string|size:10',
            'rfc' => 'required|string|size:13|unique:usuarios,rfc',
            'correo' => 'required|string|email|max:255|unique:usuarios,correo',
        ];
    }
}
