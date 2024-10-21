<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAjusteRequest extends FormRequest
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
            'id_ajuste_catalogo' => 'required|integer',
            'id_modelo_dueno' => 'required|integer',
            'modelo_dueno' => 'required|in:toma,usuario',
            'id_operador' => 'required|integer',
            'comentario' => 'nullable|string',
            'cargos_ajustados' => 'required|array',
            'cargos_ajustados.*.id_cargo' => 'required|integer',
            'cargos_ajustados.*.monto_bonificado' => 'required|numeric|min:0'
        ];
    }
}
