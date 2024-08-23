<?php

namespace App\Http\Resources;

use App\Models\ConceptoCatalogo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TarifaConceptoDetalleResource extends JsonResource
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
            "id_tipo_toma" => $this->id_tipo_toma,
            "id_concepto" => $this->id_concepto,
            "monto" => $this->monto,
            //"nombre_concepto" => $this->concepto->nombre,
        ];
    }
}
