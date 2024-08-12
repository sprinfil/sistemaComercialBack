<?php

namespace App\Http\Resources;

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
            "genera_iva" => $this->genera_iva,
            "abonable"=>$this->abonable,
            "tarifa_fija"=>$this->tarifa_fija,
            "cargo_directo"=>$this->cargo_directo,
            "genera_orden"=>$this->genera_orden,
            'tarifas' =>TarifaConceptoDetalleResource::collection($this->tarifas) //TarifaConceptoDetalleResource::collection($this->tarifas),
        ];
     }
}
