<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrdenTrabajoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "id_toma" => "required|exists:toma,id",
            "id_empleado_asigno" => "sometimes|exists:operadores,id",
            "id_empleado_encargado" => "sometimes|exists:operadores,id",
            "id_orden_trabajo_catalogo" => "required|exists:orden_trabajo_catalogos,id",
            "estado"=>"required|in:no asignada,concluida,En proceso",
            "fecha_finalizada"=>"sometimes|date",
            "obervaciones"=>"nullable|string",
            "evidencia"=>"nullable|string",
            "material_utilizado"=>"nullable|string",
        ];
    }
}
