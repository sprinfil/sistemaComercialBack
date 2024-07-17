<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTarifaServiciosDetalleRequest extends FormRequest
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
            "id_tarifa"=> "required|int",
            "id_tipo_toma"=> "required|int",
            "rango"=> "required|int|gt:0",
            "agua"=> "required|numeric|gt:0",
            "alcantarillado"=> "required|numeric|gt:0",
            "saneamiento"=> "required|numeric|gt:0",
        ];
    }
}
