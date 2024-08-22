<?php

namespace App\Http\Resources;

use App\Models\OrdenTrabajoCatalogo;
use App\Models\TarifaConceptoDetalle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PHPUnit\Event\TestSuite\Loaded;

class ConceptoResource extends JsonResource
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
            "estado" => $this->estado,
            "prioridad_abono" => $this->prioridad_abono,
            "prioridad_por_antiguedad" => $this->prioridad_por_antiguedad,
            "genera_iva" => $this->genera_iva,
            "abonable"=>$this->abonable,
            "tarifa_fija"=>$this->tarifa_fija,
            "cargo_directo"=>$this->cargo_directo,
            "genera_orden"=>$this->genera_orden,
            "genera_recargo"=>$this->genera_recargo,
            "concepto_rezago"=>$this->concepto_rezago,
            "pide_monto"=>$this->pide_monto,
            "bonificable"=>$this->bonificable,
            "recargo"=>$this->recargo,
            "concepto_rezago_data"=>$this->whenLoaded('conceptoResago'),
            "genera_orden_data"=>$this->whenLoaded('ordenAsignada'),
            "tarifas"=>TarifaConceptoDetalleResource::collection($this->tarifas) //TarifaConceptoDetalleResource::collection($this->tarifas),
        ];
     }
}
