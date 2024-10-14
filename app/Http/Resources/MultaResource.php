<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MultaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'id_multado'=>$this->id_multado,
            'id_catalogo_multa'=>$this->id_catalogo_multa,
            'id_operador'=>$this->id_operador,
            'id_revisor'=>$this->id_revisor,
            'modelo_multado'=>$this->modelo_multado,
            'motivo'=>$this->motivo,
            'fecha_solicitud'=>$this->fecha_solicitud,
            'fecha_revision'=>$this->fecha_revision,
            'monto' =>$this->monto,
            'estado'=>$this->estado,
            'codigo_toma'=>$this->origen->codigo_toma ?? null,
            'giro_comercial'=>$this->origen->giroComercial->nombre ?? null,
            'ruta'=>$this->origen->ruta->nombre ?? null,
            'libro'=>$this->origen->libro->nombre ?? null,
            'tipo_toma'=>$this->origen->tipoToma->nombre ?? null,
            'clave_catastral'=>$this->origen->clave_catastral ?? null,
            'calle'=>$this->origen->calle ?? null,
            'entre_calle_1'=>$this->origen->entre_calle_1 ?? null,
            'entre_calle_2'=>$this->origen->entre_calle_2 ?? null,
            'colonia'=>$this->origen->colonia ?? null,
            'codigo_postal'=>$this->origen->codigo_postal ?? null,
            'numero_casa'=>$this->origen->numero_casa ?? null,
            'localidad'=>$this->origen->localidad ?? null,
            'tipo_servicio'=>$this->origen->tipo_servicio,
            'tipo_contrato'=>$this->origen->tipo_contratacion,
            'nombre_multado' => $this->origen->usuario->getNombreCompletoAttribute(),
            'nombre_multa'=>$this->catalogo_multa->nombre ?? null,
            'operador_levanto_multa'=>$this->operador->getNombreCompletoAttribute(),
            'nombre_operador_revisor'=>$this->operador_revisor->getNombreCompletoAttribute(),




        ];     
    }
}
