<?php

namespace App\Http\Resources;

use App\Models\Contrato;
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
            'id_contrato' => $this->id_contrato,
            'id_solicitante' => $this->id_solicitante,
            'solicitante' => $this->solicitante->nombre, // Asumiendo que tienes una relación solicitante
            'id_revisor' => $this->id_revisor,
            'revisor' => $this->revisor ? $this->revisor->nombre : null ?? 'pendiente', // Si tienes una relación revisor
            'estado' => $this->estado,
            'agua_estado_factible'=>$this->agua_estado_factible,
            'alcantarillado_estado_factible'=>$this->alc_estado_factible,
            'saneamiento_estado_factible'=>$this->san_estado_factible,
            'derechos_conexion' => $this->derechos_conexion,
            'contrato' => $this->whenLoaded('contrato', function () {
                return new ContratoResource($this->contrato);
            }),
            'url_documento' => $this->documento ?? "ninguna", // url
            'fecha_solicitud' => $this->created_at->format('Y-m-d H:i'),
            'fecha_actualizacion' => $this->updated_at->format('Y-m-d H'),
            'ubicacion' => $this->contrato->toma->posicion
        ];
    }
}
