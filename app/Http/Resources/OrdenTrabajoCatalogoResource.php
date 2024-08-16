<?php

namespace App\Http\Resources;

use App\Models\OrdenTrabajoAccion;
use App\Models\OrdenTrabajoCatalogo;
use App\Models\OrdenTrabajoConfiguracion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use function PHPUnit\Framework\isNull;

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
            "nombre" => $this->nombre,
            "descripcion" => $this->descripcion,
            "vigencias" => $this->vigencias,
            "momento_cargo" => $this->momento_cargo,
            "genera_masiva" => $this->genera_masiva,
            'orden_trabajo_accion' => $this->orden_trabajo_acciones==null ?OrdenTrabajoAccionResource::collection($this->whenLoaded('ordenTrabajoAccion')) :OrdenTrabajoAccionResource::collection($this->orden_trabajo_acciones),
            'ordenes_trabajo_cargos' => $this->ordenes_trabajo_cargos==null ?OrdenesTrabajoCargoResource::collection($this->whenLoaded('ordenTrabajoCargos')) :OrdenesTrabajoCargoResource::collection($this->ordenes_trabajo_cargos),
            'ordenes_trabajo_encadenadas' => $this->ordenes_trabajo_encadenadas==null ?OrdenesTrabajoEncadenadaResource::collection($this->whenLoaded('ordenTrabajoEncadenado')) :OrdenesTrabajoEncadenadaResource::collection($this->ordenes_trabajo_encadenadas),
            
        ];
    }
}
