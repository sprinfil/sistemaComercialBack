<?php

namespace App\Http\Resources;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
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
            "codigo_usuario" => $this->codigo_usuario,
            "nombre" => $this->nombre,
            "apellido_paterno" => $this->apellido_paterno,
            "apellido_materno" => $this->apellido_materno,
            "nombre_contacto" => $this->nombre_contacto,
            "telefono" => $this->telefono,
            "curp" => $this->curp,
            "rfc" => $this->rfc,
            "correo" => $this->correo,
            'tomas' => TomaResource::collection($this->whenLoaded('tomas')),
            'contratos' => ContratoResource::collection($this->whenLoaded('contratos')),
            'descuento_asociado' => new DescuentoAsociadoResource($this->whenLoaded('descuento_asociado')),
            'cargos' => CargoResource::collection($this->whenLoaded(('cargosVigentes')))
        ];
    }
  
}
