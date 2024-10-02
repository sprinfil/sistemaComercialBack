<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCfdiRequest extends FormRequest
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
            'folio'=>'required|string',
            'id_timbro'=>'required|int',
            'metodo'=>'required|in:pendiente,masivo,directo',
            'estado'=>'required|in:pendiente,fallido,realizado,cancelado',
            'documento'=>'nullable|string'
        ];
    }
}
