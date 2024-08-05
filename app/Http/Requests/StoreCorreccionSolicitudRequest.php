<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCorreccionSolicitudRequest extends FormRequest
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

            "id_tipo"=>"required|int",
            "id_empleado_solicita"=>"required|int",  
            "id_empleado_registra"=>"required|int",
            "tipo_correccion"=>"required|string|max:20|
            in:Toma,Medidor,Informacion personal",
            "fecha_solicitud"=>"required|date",
            "fecha_correccion"=>"nullable|date",
            "comentario"=>"nullable|string|max:100",            
        ];
    }
}
