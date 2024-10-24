<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AjusteResource extends JsonResource
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
            'id_ajuste_catalogo' => $this->id_ajuste_catalogo,
            'id_modelo_dueno' => $this->id_modelo_dueno,
            'modelo_dueno' => $this->modelo_dueno,
            'id_operador' => $this->id_operador,
            // 'operador' => $this->operador ? $this->operador->nombre : null ?? 'sin operador',
            'estado' => strtoupper($this->estado),
            'monto_ajustado' => $this->monto_ajustado,
            'monto_total' => $this->monto_total,
            'comentario' => $this->comentario ?? 'ninguno',
            'motivo_cancelacion' => $this->motivo_cancelacion  ?? 'ninguno',
            'fecha_solicitud' => $this->created_at->format('Y-m-d H:i'),
            'fecha_actualizacion' => $this->updated_at->format('Y-m-d H:i'),
        ];
    }
}
