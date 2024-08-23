<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCajaCatalogoRequest extends FormRequest
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
            "id_cuenta_contable"=>"required|integer|gt:0",
            "nombre_caja"=>"required|string",
            "hora_apertura"=>"required|date_format:H:i:s",
            "hora_cierre"=>"required|date_format:H:i:s|after:hora_apertura",
        ];
    }
}
