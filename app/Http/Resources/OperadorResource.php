<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperadorResource extends JsonResource
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
            "codigo_empleado" => $this->codigo_empleado,
            "nombre_completo" => $this->nombre_completo,
            "nombre" => $this->nombre,
            "apellido_paterno" => $this->apellido_paterno,
            "apellido_materno" => $this->apellido_materno,
            "CURP" => $this->CURP,
            "fecha_nacimiento" => $this->fecha_nacimiento,
            "user" => $this->user,
            "roles" => $this->roles,
        ];
    }
}
