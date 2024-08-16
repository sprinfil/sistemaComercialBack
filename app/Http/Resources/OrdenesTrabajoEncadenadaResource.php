<?php

namespace App\Http\Resources;

use App\Models\OrdenTrabajoCatalogo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdenesTrabajoEncadenadaResource extends JsonResource
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
            "id_OT_Catalogo_padre" => $this->id_OT_Catalogo_padre,
            "id_OT_Catalogo_encadenada" => $this->id_OT_Catalogo_encadenada,
            "OT_CatalogoPadre" => new OrdenTrabajoCatalogoResource($this->whenLoaded('OrdenCatalogoPadre')),
            "OT_Encadenada" => new OrdenTrabajoCatalogoResource($this->whenLoaded('OrdenCatalogoEncadenadas')),
        ];
    }
}
