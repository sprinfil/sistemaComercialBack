<?php

namespace App\Http\Resources;

use App\Models\DatoFiscal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CfdiResource extends JsonResource
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
            'folio' => $this->folio,
            // Agrega los pagos solo si existen y están cargados
            'id_timbro' => $this->id_timbro,
            'timbro' => $this->timbro->nombre,
            'metodo' => $this->metodo,
            'estado' => $this->estado,
            'url_documento' => $this->documento ?? 'ninguno',
            'fecha_solicitud' => $this->created_at->format('Y-m-d H:i'),
            'fecha_actualizacion' => $this->updated_at->format('Y-m-d H'),
            'pagos' => $this->whenLoaded('pagos', function () {
                return $this->pagos->map(function ($pago) {
                    return [
                        'id' => $pago->id,
                        'id_dueno' => $pago->id_dueno,
                        'modelo_dueno' => $pago->modelo_dueno,
                        'total_pagado' => $pago->total_pagado,
                        'forma_pago' => $pago->forma_pago,
                        'fecha_pago' => $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('Y-m-d H:i') : null,
                    ];
                });
            }),
            'datos_fiscales' => $this->whenLoaded('datoFiscal', function () {
                return new DatoFiscalResource($this->datoFiscal);
            }),
        ];
    }
}