<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RetiroCajaResource extends JsonResource
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
            "id_sesion_caja" => $this->id_sesion_caja,
            "cantidad_centavo_10" => $this->cantidad_centavo_10,
            "cantidad_centavo_20" => $this->cantidad_centavo_20,
            "cantidad_centavo_50" => $this->cantidad_centavo_50,
            "cantidad_moneda_1" => $this->cantidad_moneda_1,
            "cantidad_moneda_2" => $this->cantidad_moneda_2,
            "cantidad_moneda_5" => $this->cantidad_moneda_5,
            "cantidad_moneda_10" => $this->cantidad_moneda_10,
            "cantidad_moneda_20" => $this->cantidad_moneda_20,
            "cantidad_billete_20" => $this->cantidad_billete_20,
            "cantidad_billete_50" => $this->cantidad_billete_50,
            "cantidad_billete_100" => $this->cantidad_billete_100,
            "cantidad_billete_200" => $this->cantidad_billete_200,
            "cantidad_billete_500" => $this->cantidad_billete_500,
            "cantidad_billete_1000" => $this->cantidad_billete_1000,
            "monto_total" => $this->monto_total,
        ];
    }
}
