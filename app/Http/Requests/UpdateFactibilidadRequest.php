<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFactibilidadRequest extends FormRequest
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
            //"estado_factible"=>"required|in:factible,no_factible" ,
            //"derechos_conexion"=>"numeric|nullable|min:0",
            'id_revisor'=>'required|int',
            'estado'=>'required|in:pendiente,rechazada,pendiente_de_pago,pagada',
            'agua_estado_factible'=>'required|in:no_factible,factible',
            'alc_estado_factible'=>'required|in:no_factible,factible',
            'san_estado_factible'=>'required|in:no_factible,factible',
            'derechos_conexion'=>'numeric|nullable|min:0',
            'documento' => 'nullable|file|mimes:pdf|max:2048'
        ];
    }
}
