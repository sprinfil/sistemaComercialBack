<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDescuentoAsociadoRequest extends FormRequest
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
            "id_descuento"=>"required|int",
            "id_modelo"=>"required|int",
            "modelo_dueno"=>"required|in:toma,usuario",
            //"id_evidencia"=>" nullable|int", //Segun se ocupa guardar el file y al guardar el id_evidencia
            "evidencia"=> "nullable|array",
            "evidencia.*" => "file",
            "curp" => "required|string|size:18|unique:usuarios,curp",
            "id_registra"=>"required|int",
            "vigencia"=>"nullable|date",
            "estatus"=>"required|in:vigente,no_vigente",
            "folio"=>"nullable|string",
        ];
    }
}
