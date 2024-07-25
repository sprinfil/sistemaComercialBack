<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DatoFiscalResource extends JsonResource
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
            "id_modelo" => $this->id_modelo,
            "modelo" => $this->modelo,
            "regimen_fiscal" => $this->regimen_fiscal,
            "correo" => $this-> correo,
            "razon_social"=> $this->razon_social,
            "telefono"=> $this->telefono,
            "pais"=> $this->pais,
            "estado"=> $this->estado,
            "municipio"=> $this->municipio,
            "localidad"=> $this->localidad,
            "colonia"=> $this->colonia,
            "calle"=> $this->calle,
            "referencia"=> $this->referencia,
            "numero_exterior"=> $this->numero_exterior,
            "codigo_postal"=> $this->codigo_postal,
            "contacto"=> $this->contacto,
        ];
    }
}