<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use function PHPUnit\Framework\isNull;

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
        $calle= $this->relationLoaded('calle1')?new CalleResource($this->calle1) : $this->calle ;
        $calleEntre1= $this->relationLoaded('entre_calle_1')? new CalleResource($this->entre_calle_1) : $this->entre_calle1 ;
        $calleEntre2= $this->relationLoaded('entre_calle_2')? new CalleResource($this->entre_calle_2) : $this->entre_calle2 ;
        $colonia= $this->relationLoaded('colonia1')? new ColoniaResource($this->colonia1) : $this->colonia ;
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
            "municipio" => $this->municipio,
            "localidad" => $this->localidad,
            "colonia" =>$colonia,
            "calle" => $calle, //new CalleResource($this->whenLoaded('calle1')) ?? $this->calle,
            "entre_calle1" =>$calleEntre1,
            "entre_calle2" => $calleEntre2,
            "num_casa" => $this->num_casa,
            "diametro_toma" => $this->diametro_toma,
            "codigo_postal" => $this->codigo_postal,
            "coordenada" => $this->coordenada,
            "usuario" =>new UsuarioResource($this->whenLoaded('usuario')),
            "toma" => new TomaResource($this->whenLoaded('toma')),
            
        ];
    }
}
