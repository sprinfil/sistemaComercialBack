<?php

namespace App\Http\Resources;

use App\Models\OrdenTrabajoCatalogo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use function PHPUnit\Framework\isNull;

class TomaFactibilidadResource extends JsonResource
{
    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $calle2 = !empty($this->entre_calle_2) ? " y " . $this->entre_calle_2 : null;
        return [
            "id" => $this->id,
            "codigo_toma" => $this->codigo_toma,
            "estatus" => $this->estatus,
            "clave_catastral" => $this->clave_catastral,
            "calle" => $this->calle,
            "entre_calle_1" => $this->entre_calle_1,
            "entre_calle_2" => $this->entre_calle_2,
            "colonia" => $this->colonia,
            "codigo_postal" => $this->codigo_postal,
            "numero_casa" => $this->numero_casa,
            "localidad" => $this->localidad,
            "posicion" => $this->posicion,
            "direccion_completa" => $this->calle . "/" . $this->entre_calle_1 . $calle2 . " #" . $this->numero_casa . " " . $this->colonia,
            'tipo_toma' => new TipoTomaResource($this->whenLoaded('tipoToma')),
            'libro' => new LibroSimplificado($this->whenLoaded('libro')),
            'ruta' => new RutaSimplificado($this->whenLoaded('ruta')),
            'usuario' => new UsuarioResource($this->whenLoaded('usuario')),
            'giroComercial' => new GiroComercialCatalogoResource($this->whenLoaded('giroComercial')),
        ];
        //return parent::toArray($request);
    }
    protected function hasRequestedSaldo($request)
    {
        // Example: Check if a query parameter indicates saldo should be included
        return $request->has('cargos');
    }
}
