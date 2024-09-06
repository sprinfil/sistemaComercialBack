<?php

namespace App\Http\Resources;

use App\Models\Operador;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            "id_empleado_genero" => $this->id_empleado_genero,
            "id_empleado_asigno" => $this->id_empleado_asigno,
            "id_empleado_encargado" => $this->id_empleado_encargado,
            "id_orden_trabajo_catalogo" => $this->id_orden_trabajo_catalogo,
            "estado" => $this->estado,
            "fecha_asignacion" => $this->fecha_asignacion,
            "fecha_finalizada" => $this->fecha_finalizada,
            "fecha_vigencia" => $this->fecha_vigencia,
            "obervaciones" => $this->obervaciones,
            "material_utilizado" => $this->material_utilizado,
            "evidencia" => $this->evidencia,
            "posicion_OT" => $this->posicion_OT,
            "genera_OT_encadenadas" => $this->genera_OT_encadenadas,
            "created_at" =>  Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'toma' =>new TomaResource($this->whenLoaded('toma')),
            'empleadoAsigno' =>new OperadorResource($this->whenLoaded('empleadoAsigno')),
            'empleadoEncargado' =>new OperadorResource($this->whenLoaded('empleadoEncargado')),
            'orden_trabajo_catalogo' =>new OrdenTrabajoCatalogoResource($this->whenLoaded('ordenTrabajoCatalogo')),
            'cargos' => CargoResource::collection($this->whenLoaded('cargosVigentes')),
           
        ];
    }
}
