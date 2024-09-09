<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContratoResource extends JsonResource
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
            "id_toma" => $this->id_toma,
            "id_usuario" => $this->id_usuario,
            "folio_solicitud" => $this->folio_solicitud,
            "estatus" => $this->estatus,
            "nombre_contrato" => $this->nombre_contrato,
            "clave_catastral" => $this->clave_catastral,
            "tipo_toma" => $this->tipo_toma,
            "servicio_contratado" => $this->servicio_contratado,
            "colonia" => $this->colonia,
            "calle" => $this->calle,
            "municipio" => $this->municipio,
            "localidad" => $this->localidad,
            "colonia" => $this->colonia,
            "calle" => $this->calle,
            "entre_calle1" => $this->entre_calle1,
            "entre_calle2" => $this->entre_calle2,
            "domicilio" => $this->domicilio,
            "diametro_de_la_toma" => $this->diametro_de_la_toma,
            "codigo_postal" => $this->codigo_postal,
            "coordenada" => $this->coordenada,
            "usuario" =>new UsuarioResource($this->whenLoaded('usuario')),
            "toma" => new TomaResource($this->whenLoaded('toma')),
        ];
    }
}
