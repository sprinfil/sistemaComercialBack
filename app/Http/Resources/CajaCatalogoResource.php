<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CajaCatalogoResource extends JsonResource
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
            "id_caja" => $this->id_caja,
            "tipo_caja" => $this->tipo_caja,
            "hora_apertura" => $this->hora_apertura,
            "hora_cierre" => $this->hora_cierre,
        ];
    }
}
