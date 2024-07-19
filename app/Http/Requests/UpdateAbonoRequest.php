<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAbonoRequest extends FormRequest
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
            "id_cargo"=>"required|integer",
            "id_origen"=>"required|integer",
            "modelo_origen"=>"required|string|max:55",
            "total_abonado"=>"required|numeric|regex:/^\d+(\.\d{1,2})?$/",
        ];
    }
}
