<?php

namespace App\Http\Resources;

use App\Models\OrdenTrabajoCatalogo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use function PHPUnit\Framework\isNull;

class TomaResource extends JsonResource
{
    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //$calle2=;
        return [
            "id" => $this->id,
            "posicion" => $this->posicion,
            "id_usuario" => $this->id_usuario,
            "id_giro_comercial" => $this->id_giro_comercial,
            "id_libro" => $this->id_libro,
            "codigo_toma" => $this->codigo_toma,
            "id_tipo_toma" => $this->id_tipo_toma,
            "estatus" => $this->estatus,
            "clave_catastral" => $this->clave_catastral,
            "calle" => $this->calle,
            "entre_calle_1" => $this->entre_calle_1,
            "entre_calle_2" => $this->entre_calle_2,
            "colonia" => $this->colonia,
            "codigo_postal" => $this->codigo_postal,
            "numero_casa" => $this->numero_casa,
            "localidad" => $this->localidad,
            "direccion_completa"=>$this->calle."/".$this->entre_calle_1." y ".$this->entre_calle_2." #".$this->numero_casa." ".$this->colonia,
            "diametro_toma" => $this->diametro_toma,
            "calle_notificaciones" => $this->calle_notificaciones,
            "entre_calle_notificaciones_1" => $this->entre_calle_notificaciones_1,
            "entre_calle_notificaciones_2" => $this->entre_calle_notificaciones_2,
            "tipo_servicio" => $this->tipo_servicio,
            "tipo_contratacion" => $this->tipo_contratacion,
            "c_agua" => $this->c_agua,
            "c_alc" => $this->c_alc,
            "c_san" => $this->c_san,
            'tipo_toma' => new UsuarioResource($this->whenLoaded('tipoToma')),
            'libro' => new LibroSimplificado($this->whenLoaded('libro')),
            'ruta' => new RutaSimplificado($this->whenLoaded('ruta')),
            'usuario' => new UsuarioResource($this->whenLoaded('usuario')),
            'contratos' => ContratoResource::collection($this->whenLoaded('contratovigente')),
            'giroComercial' => new GiroComercialCatalogoResource($this->whenLoaded('giroComercial')),
            'medidor' => $this->whenLoaded('medidor'),
            'consumo' => $this->whenLoaded('consumo'),
            'ordenes_trabajo' => OrdenTrabajoResource::collection($this->whenLoaded('ordenesTrabajo')),
            'cargos' => CargoResource::collection($this->whenLoaded(('cargosVigentes'))),
            'saldo' =>  isset($this->saldo) ? $this->saldo : null,
        ];
        //return parent::toArray($request);
    }
    protected function hasRequestedSaldo($request)
    {
        // Example: Check if a query parameter indicates saldo should be included
        return $request->has('cargos');
    }
}
