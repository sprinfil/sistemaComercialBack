<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LibroResource extends JsonResource
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
            "id_ruta" => $this->id_ruta,
            "nombre" => $this->nombre,
            "latitud" => $this->latitud,
            "longitud" => $this->longitud,
        ];
    }
}
