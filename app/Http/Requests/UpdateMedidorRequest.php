<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedidorRequest extends FormRequest
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
            "id_toma"=>"required|integer",
            "numero_serie"=>"required|string|max:55|alpha:ascii",
            "marca" => "required|string|max:55|alpha:ascii",
            "diametro" => "required|string|max:55|alpha:ascii",
            "tipo" => "required|string|max:55|alpha:ascii",
        ]; 
    }
}
