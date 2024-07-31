<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PuntoResource extends JsonResource
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
            "id_asignacion_geografica" => $this->id_asignacion_geografica,
            "latitud" => $this->latitud,
            "longitud" => $this->longitud,
        ];
    }
}
