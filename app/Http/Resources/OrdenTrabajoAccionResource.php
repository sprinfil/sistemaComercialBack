<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdenTrabajoAccionResource extends JsonResource
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
            "id_orden_trabajo_catalogo" => $this->id_orden_trabajo_catalogo,
            "accion" => $this->accion,
            "modelo" => $this->modelo,
            "opcional" => $this->opcional,
            "id_orden_trabajo_acc_encadena" => $this->id_orden_trabajo_acc_encadena,
            "id_orden_trabajo_acc_alterna" => $this->id_orden_trabajo_acc_alterna,
        ];
    }
}
