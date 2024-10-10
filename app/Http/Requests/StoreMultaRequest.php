<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMultaRequest extends FormRequest
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
            //'id_multado' => 'required|integer',
            'id_catalogo_multa' => 'required|integer',
            'id_operador' => 'required|integer',
            'id_revisor' => 'required|integer',
            'modelo_multado' => 'required|string',
            'motivo' => 'nullable|string',
            'fecha_solicitud' => 'required|date',
            'fecha_revision' => 'required|date',
            'monto' => 'required|integer',
            'estado' => 'required|in:activo,rechazado,pendiente,cancelado'
        ];
    }
}
