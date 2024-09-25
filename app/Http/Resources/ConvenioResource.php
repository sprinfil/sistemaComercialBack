<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConvenioResource extends JsonResource
{
    public static $wrap = false;

    protected $incluirLetrasPendientes = false;

    // Método para activar la inclusión de letras pendientes
    public function withLetrasPendientes($value = true)
    {
        $this->incluirLetrasPendientes = $value;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_convenio_catalogo' => $this->id_convenio_catalogo,
            'convenio_catalogo' => new ConvenioCatalogoResource($this->whenLoaded('ConvenioCatalogo')),
            'id_modelo' => $this->id_modelo,
            'modelo_origen' => $this->modelo_origen,
            'monto_conveniado' => $this->monto_conveniado,
            'monto_total' => $this->monto_total,
            'periodicidad' => $this->periodicidad,
            'cantidad_letras' => $this->cantidad_letras,
            'estado' => $this->estado,
            'comentario' => $this->comentario,
            'letras' => LetraResource::collection($this->whenLoaded('letra')),
            'cargos_conveniados' => CargoResource::collection($this->whenLoaded('CargosConveniados')),
            'origen' => $this->whenLoaded('origen'),
            $this->mergeWhen($this->incluirLetrasPendientes, [
                'letras_pendientes' => $this->getLetrasPendientes()
            ]),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }

    // Método para obtener las letras pendientes
    private function getLetrasPendientes()
    {
        $letras_pendientes = [];

        foreach ($this->letra as $letra) {
            $cargo_vigente = $letra->cargosVigentes;
            if ($cargo_vigente->isEmpty()) {
                $letras_pendientes[] = $letra;
            }
        }

        return $letras_pendientes;
    }
}
