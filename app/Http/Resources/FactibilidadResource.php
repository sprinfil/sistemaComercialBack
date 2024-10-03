<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FactibilidadResource extends JsonResource
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
            'id' => $this->id,
            'id_toma' => $this->id_toma,
            'id_contrato' => $this->id_contrato,
            'id_solicitante' => $this->id_solicitante,
            'solicitante' => $this->solicitante->nombre, // Asumiendo que tienes una relación solicitante
            'id_revisor' => $this->id_revisor,
            'revisor' => $this->revisor ? $this->revisor->nombre : null ?? 'sin revisor', // Si tienes una relación revisor
            'estatus' => strtoupper($this->estado),
            'servicio' => strtoupper($this->servicio),
            'estado_servicio' => strtoupper($this->estado_servicio),
            //'saneamiento_estado_factible' => $this->san_estado_factible,
            'derechos_conexion' => $this->derechos_conexion ?? 0,
            'toma' => $this->whenLoaded('toma', function () {
                return new TomaFactibilidadResource($this->toma);
            }),
            //'url_documento' => $this->documento ?? "ninguna", // url
            'archivos' => $this->whenLoaded('archivos', function () {
                return ArchivoResource::collection($this->archivos);
            }),
            'comentario' => $this->comentario ?? 'ninguno',
            'fecha_solicitud' => $this->created_at->format('Y-m-d H:i'),
            'fecha_actualizacion' => $this->updated_at->format('Y-m-d H:i'),
            'ubicacion' => $this->toma->posicion
        ];
    }
}
