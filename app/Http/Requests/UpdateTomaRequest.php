<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTomaRequest extends FormRequest
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
           
            "estatus"=>"required|string|max:55|
               in:pendiente confirmacion de inspeccion,pendiente de inspeccion,pendiente de instalacion,activa,baja temporal,baja definitiva,en proceso",
            "calle"=>"required|string|max:55",
            "entre_calle_1"=>"nullable|string|max:55",
            "entre_calle_2"=>"nullable|string|max:55",
            "colonia"=>"required|string|max:55",
            "codigo_postal"=>"required|string|max:5",
            "localidad"=>"required|string|max:55",
            "diametro_toma"=>"required|string|max:5",
            "calle_notificaciones"=>"required|string|max:55",
            "entre_calle_notificaciones_1"=>"nullable|string|max:55",
            "entre_calle_notificaciones_2"=>"nullable|string|max:55",
            "tipo_servicio"=>"required|string|max:55|
            in:lectura,promedio",
            "tipo_toma"=>"required|string|max:55|
               in:domestico,comercial,industrial,publico,especial",
            "tipo_contratacion"=>"required|string|max:55|
               in:normal,condicionado,desarrollador,comercial,industrial,clandestina",
        ];
    }
}
