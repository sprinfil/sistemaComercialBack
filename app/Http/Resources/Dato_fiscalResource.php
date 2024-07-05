<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Dato_fiscalResource extends JsonResource
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
            "regimen_fiscal" => $this->regimen_fiscal,
            "correo" => $this->correo,
            "razon_social" => $this->razon_social,
            "telefono" => $this->telefono,
            "pais" => $this->pais,
            "estado" => $this->estado,
            "municipio" => $this->municipio,
            "localidad" => $this->localidad,
            "colonia" => $this->colonia,
            "calle" => $this->calle,
            "referencia" => $this->referencia,
            "numero_exterior" => $this->numero_exterior,
            "codigo_postal" => $this->codigo_postal,
            "tipo_modelo" => $this->codigo_postal,
        ];
        //return parent::toArray($request);
    
    }
}
