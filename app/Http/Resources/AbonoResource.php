<?php

namespace App\Http\Resources;

use App\Models\Cargo;
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
            "cargo" => Cargo::find($this->id_cargo)->nombre,
            "nombre" => Cargo::find($this->id_cargo)->nombre,
            "estado" => Cargo::find($this->id_cargo)->estado,
            "monto_abonado" => number_format($this->total_abonado, 2, '.', ''),
            "monto_inicial" => number_format($this->when(true, function () {
                return Cargo::find($this->id_cargo)->montoOriginal(); // Suponiendo que el método en el modelo se llama montoPendiente()
            }), 2, '.', ''),
            "monto_pendiente" => number_format($this->when(true, function () {
                return Cargo::find($this->id_cargo)->montoPendiente(); // Suponiendo que el método en el modelo se llama montoPendiente()
            }), 2, '.', ''),

        ];
    }
}