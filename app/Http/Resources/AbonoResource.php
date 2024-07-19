<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbonoResource extends JsonResource
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
            "id_cargo" => $this->id_cargo,
            "id_origen" => $this->id_origen,
            "modelo_origen" => $this->modelo_origen,
            "total_abonado" => $this->total_abonado,
        ];
    }
}