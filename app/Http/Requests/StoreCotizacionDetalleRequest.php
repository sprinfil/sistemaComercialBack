<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCotizacionDetalleRequest extends FormRequest
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
            "cotizacion_detalle"=>"required|array",
            "cotizacion_detalle.*"=>"required|array",//:id_cotizacion,id_sector,nombre_concepto,monto
            "cotizacion_detalle.*.id_cotizacion"=>"required|exists:cotizaciones,id",
            "cotizacion_detalle.*.id_sector"=>"sometimes", #todo
            "cotizacion_detalle.*.id_concepto.*"=>"required|string",
            "cotizacion_detalle.*.monto"=>"required|numeric",
        ];
    }
}
