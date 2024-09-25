<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DescuentoAsociadoResource extends JsonResource
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
            "id_descuento" => $this->id_descuento,
            "id_modelo" => $this->id_modelo,
            "modelo_dueno" => $this->modelo_dueno,
            "curp" => $this->curp,
            "id_evidencia" => $this->id_evidencia,
            "id_registra" => $this->id_registra,
            "vigencia" => $this->vigencia,
            "estatus" => $this->estatus,
            "folio" => $this->folio,
            "descuento_catalogo" => $this->descuento_catalogo,
             "archivo" => $this->when($this->archivo, function (){
                return [
                    'url' => Storage::url('evidencia/' .$this->archivo->url),
                ];
            })
        ];
    }
}
