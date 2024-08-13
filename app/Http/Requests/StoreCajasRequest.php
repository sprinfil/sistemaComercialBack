<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCajasRequest extends FormRequest
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
            "id_operador"=>"required|integer|gt:0",
            "fecha_apertura"=>"required|date_format:Y-m-d H:i:s",
            "fecha_apertura"=>"required|date_format:Y-m-d H:i:s",
        ];
    }
}
