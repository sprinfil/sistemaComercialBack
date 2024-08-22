<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdenTrabajoResource extends JsonResource
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
            "id_toma" => $this->id_toma,
            "id_empleado_asigno" => $this->id_empleado_asigno,
            "id_empleado_encargado" => $this->id_empleado_encargado,
            "id_orden_trabajo_catalogo" => $this->id_orden_trabajo_catalogo,
            "estado" => $this->estado,
            "fecha_finalizada" => $this->fecha_finalizada,
            "fecha_vigencia" => $this->fecha_vigencia,
            "obervaciones" => $this->obervaciones,
            "material_utilizado" => $this->material_utilizado,
            "evidencia" => $this->evidencia,
            "posicion_OT" => $this->posicion_OT,
            'orden_trabajo_catalogo' => OrdenTrabajoCatalogoResource::collection($this->whenLoaded('ordenTrabajoCatalogo')),
        ];
    }
}
