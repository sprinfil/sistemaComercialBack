<?php

namespace App\Http\Resources;

use App\Models\DatoFiscal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeriodoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_ruta' => $this->id_ruta,
            'ruta' => new RutaResource($this->whenLoaded('tieneRutas')),
            'id_tarifa' => $this->id_tarifa,
            'tarifa' => new TarifaResource($this->whenLoaded('tarifa')),
            'nombre' => $this->nombre,
            'periodo' => $this->periodo,
            'facturacion_fecha_inicio' => $this->facturacion_fecha_inicio,
            'facturacion_fecha_final' => $this->facturacion_fecha_final,
            'lectura_inicio' => $this->lectura_inicio,
            'lectura_final' => $this->lectura_final,
            "recibo_inicio" => $this->recibo_inicio,
            "recibo_final" => $this->recibo_final,
            "estatus" => $this->estatus,
            'facturas' => FacturaResource::collection($this->whenLoaded('factura')),
            //'carga_trabajo' => CargaTrabajoResource::collection($this->whenLoaded('cargaTrabajo')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}