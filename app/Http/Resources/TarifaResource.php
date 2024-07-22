<?php

namespace App\Http\Resources;

use App\Models\TarifaConceptoDetalle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TarifaResource extends JsonResource
{
    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /*
        $conceptos = [];
        foreach($this->conceptos as $concepto){
            $conceptos[] = [
                "id" => $concepto->id,
                "id_tarifa" => $concepto->id_tarifa,
                "id_tipo_toma" => $concepto->id_tipo_toma,
                "id_concepto" => $concepto->id_concepto,
                "nombre_concepto" => $concepto->concepto->nombre,
                "monto" => $concepto->monto,
            ];
        }
            */

        return [
            "id" => $this->id,
            "nombre" => $this->nombre,
            "descripcion" => $this->descripcion,
            "fecha" => $this->fecha,
            "estado" => $this->estado,
            //"servicios" => $this->servicio,
            //"conceptos" =>TarifaConceptoDetalle::all()//TarifaConceptoDetalleResource::collection($this->whenLoaded('conceptos')),
        ];
    }
}
