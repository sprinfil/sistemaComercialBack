<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConceptoCatalogoRequest extends FormRequest
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
            "nombre"=>"required|string|max:55",
            "descripcion"=>"nullable|string",
            "estado"=>"nullable|string|max:10|
            in:activo,inactivo",
            "prioridad_abono"=>"required|int",
            "prioridad_por_antiguedad"=>"nullable|boolean",
            "genera_iva"=>"nullable|boolean",
            "abonable"=>"required|boolean",
            "tarifa_fija"=>"required|boolean",
            "cargo_directo"=>"required|boolean",
            "genera_orden"=>"nullable|int",
            "genera_recargo"=>"nullable|boolean",
            "concepto_rezago"=>"nullable|int",
            "pide_monto"=>"nullable|boolean",
            "bonificable"=>"nullable|boolean",
            "recargo"=>"nullable|numeric",
            "tarifas.*"=>"nullable|array"
        ];
    }
}
