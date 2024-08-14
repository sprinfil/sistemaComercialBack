<?php

namespace App\Http\Resources;

use App\Models\OrdenTrabajoCatalogo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdenesTrabajoCargoResource extends JsonResource
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
            "id_orden_trabajo_catalogo" => $this->id_orden_trabajo_catalogo,
            "id_concepto_catalogo" => $this->id_concepto_catalogo,
            "OT_catalogo" => new OrdenTrabajoCatalogo($this->whenLoaded('')),
            "conceptos" => new OrdenTrabajoCatalogo($this->whenLoaded('')),
        ];
    }
}
