<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDatoFiscalRequest extends FormRequest
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
            "id_modelo"=>"required|integer",
            "modelo"=>"required|string|max:10",
            "regimen_fiscal"=>"required|string|max:55" ,
            "nombre"=>"required|string|max:55",
            "correo"=>"required|email|max:55|",
            "razon_social"=>"required|string|max:55",
            "telefono"=>"required|string|size:10",
            "pais"=>"required|string|max:55",
            "estado"=>"required|string|max:55",
            "municipio"=>"required|string|max:55",
            "localidad"=>"required|string|max:55",
            "colonia"=>"required|string|max:55",
            "calle"=>"string|max:55",
            "referencia"=>"string|max:55",
            "numero_exterior"=>"nullable|string|max:10", 
            "codigo_postal"=>"required|string|max:10",
        ];
       
    }
}
