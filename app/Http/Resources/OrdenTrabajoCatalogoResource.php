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
            "nombre" => $this->nombre,
            'orden_trabajo_conf' =>new OrdenTrabajoConfResource($this->orden_trabajo_conf),
            'orden_trabajo_configuracion' => OrdenTrabajoConfResource::collection($this->whenLoaded('ordenTrabajoConfiguracion')),
        ];
    }
}
