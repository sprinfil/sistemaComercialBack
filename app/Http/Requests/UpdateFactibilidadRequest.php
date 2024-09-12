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
            'id_revisor' => 'required|int',
            'estado' => 'required|in:sin revisar,rechazada,pendiente de pago,pagada',
            'agua_estado_factible' => 'required|in:pendiente,no factible,factible',
            'alc_estado_factible' => 'required|in:pendiente,no factible,factible',
            'derechos_conexion' => 'numeric|nullable|min:0',
            //'documento' => 'nullable|file|mimes:pdf|max:2048'
            'documentos' => 'nullable|array',
            'documentos.*' => 'file', // Cada archivo debe ser un PDF y tener un tamaño máximo de 2 MB
            'comentario' => 'nullable|string',
        ];
    }
}
