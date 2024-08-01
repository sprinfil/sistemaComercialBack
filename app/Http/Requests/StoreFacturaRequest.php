<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFacturaRequest extends FormRequest
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
            "id_periodo" => "required|int|gt:0",
            "id_toma" => "required|int|gt:0",
            "id_consumo" => "required|int|gt:0",
            "id_tarifa_servicio" => "required|int|gt:0",
            "monto"=> "required|numeric|gt:0",
            "fecha"=> "required|date",
        ];
    }
}
