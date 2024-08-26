<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTomaRequest extends FormRequest
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
            "id_usuario"=>"required|int",
            "id_giro_comercial"=>"required|int",
            "id_libro"=>"required|int",
            "id_codigo_toma"=>"required|int",
            "id_tipo_toma"=>"required|int",
            "clave_catastral"=>"required|string|min:10|max:18",
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
            "tipo_contratacion"=>"required|string|max:55|
               in:normal,condicionado,desarrollador,comercial,industrial,clandestina",
               "c_agua"=>"nullable|int",
               "c_alc_san"=>"nullable|int",
        ];
    }
}
