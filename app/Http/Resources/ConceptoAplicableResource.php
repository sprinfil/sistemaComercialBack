<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConceptoAplicableResource extends JsonResource
{
    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "id_concepto_catalogo" => $this->id_concepto_catalogo,
            "id_modelo" => $this->id_modelo,
            "modelo" => $this->modelo,
            "tipo_bonificacion" => $this->tipo_bonificacion,
            "porcentaje_bonificable" => $this->porcentaje_bonificable,
            "monto_bonificable" => $this->monto_bonificable,
        ];
    }
}
