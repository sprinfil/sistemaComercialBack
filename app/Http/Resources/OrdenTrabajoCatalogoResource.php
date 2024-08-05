<?php

namespace App\Http\Resources;

use App\Models\OrdenTrabajoCatalogo;
use App\Models\OrdenTrabajoConfiguracion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdenTrabajoCatalogoResource extends JsonResource
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
            "nombre" => $this->nombre,
            "vigencias" => $this->vigencias,
            "momento_cargo" => $this->momento_cargo,
            "genera_masiva" => $this->genera_masiva,
            'orden_trabajo_accion' => new OrdenTrabajoAccionResource($this->orden_trabajo_accion) ?? OrdenTrabajoAccionResource::collection($this->whenLoaded('ordenTrabajoAccion')),
        ];
    }
}
