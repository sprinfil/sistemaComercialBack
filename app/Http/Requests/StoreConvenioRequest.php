<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConvenioRequest extends FormRequest
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
            "porcentaje_conveniado"=>"required|integer|gt:0",
  
            "id_convenio_catalogo" => "required|integer|gt:0",
            "periodicidad" => "required|string|in:mensual,quincenal",
            "cantidad_letras" => "required|integer|min:1",
            "estado" => "required|string|in:activo",
            "comentario" => "nullable|string|max:50",
          
            "cargos_conveniados"=>"required|array",
            "cargos_conveniados.*.id"=>"required|integer|gt:0",
            "cargos_conveniados.*.id_concepto"=>"required|integer|gt:0",
            "cargos_conveniados.*.nombre"=>"nullable|string|max:55",
            "cargos_conveniados.*.id_origen"=>"required|integer|gt:0",
            "cargos_conveniados.*.modelo_origen"=>"required|string|max:55",
            "cargos_conveniados.*.id_dueno"=>"required|integer|gt:0",
            "cargos_conveniados.*.modelo_dueno"=>"required|string|max:55",
            "cargos_conveniados.*.monto"=>"nullable|numeric|regex:/^\d+(\.\d{1,2})?$/",
            "cargos_conveniados.*.iva"=>"nullable|numeric|regex:/^\d+(\.\d{1,2})?$/",
            "cargos_conveniados.*.estado"=>"nullable|string|max:55",
            "cargos_conveniados.*.id_convenio"=>"required|integer|gt:0",
            "cargos_conveniados.*.fecha_cargo"=>"nullable|date|max:55",
            "cargos_conveniados.*.fecha_liquidacion"=>"nullable|date|max:55",
        ];
    }
}
