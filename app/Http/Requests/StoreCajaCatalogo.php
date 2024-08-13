<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCajaCatalogo extends FormRequest
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
            "id_caja"=>"required|integer|gt:0",
            "tipo_caja"=>"required|string|
            in:Zona Urbana,Centenario,Todos Santos,El Pescadero,Los Barriles,El Sargento,Agua Amarga,Meliton Albañez,San Pedro,Reforma Agraria,El Triunfo,Albaro Obregon,Elias Calles,Los Planes,San Bartolo,Juan Dominguez Cota,San Antonio,Conquista Agraria,El Carrizal,El Cardonal,Las Pocitas,La trinidad,La Ventana",
            "hora_apertura"=>"required|date_format:H:i",
            "hora_cierre"=>"required|date_format:H:i|after:hora_apertura",
        ];
    }
}
