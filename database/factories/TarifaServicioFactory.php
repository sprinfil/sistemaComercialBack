<?php

namespace Database\Factories;

use App\Models\TarifaServicio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TarifaServicio>
 */
class TarifaServicioFactory extends Factory
{
    protected $model = TarifaServicio::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $index = 0;
         [
            $data = [
                ['id_tarifa' => 1, 'id_tipo_toma' => 1, 'genera_iva' => 0, 'tipo_servicio' => 'agua'],
                ['id_tarifa' => 1, 'id_tipo_toma' => 1, 'genera_iva' => 1, 'tipo_servicio' => 'alcantarillado'],
                ['id_tarifa' => 1, 'id_tipo_toma' => 1, 'genera_iva' => 1, 'tipo_servicio' =>'saneamiento'],

                ['id_tarifa' => 1, 'id_tipo_toma' => 2, 'genera_iva' => 1, 'tipo_servicio' => 'agua'],
                ['id_tarifa' => 1, 'id_tipo_toma' => 2, 'genera_iva' => 1, 'tipo_servicio' =>'alcantarillado'],
                ['id_tarifa' => 1, 'id_tipo_toma' => 2, 'genera_iva' => 1, 'tipo_servicio' =>'saneamiento'],

                ['id_tarifa' => 1, 'id_tipo_toma' => 3, 'genera_iva' => 1, 'tipo_servicio' =>'agua'],
                ['id_tarifa' => 1, 'id_tipo_toma' => 3, 'genera_iva' => 1, 'tipo_servicio' =>'alcantarillado'],
                ['id_tarifa' => 1, 'id_tipo_toma' => 3, 'genera_iva' => 1, 'tipo_servicio' =>'saneamiento'],

                ['id_tarifa' => 1, 'id_tipo_toma' => 4, 'genera_iva' => 1, 'tipo_servicio' =>'agua'],
                ['id_tarifa' => 1, 'id_tipo_toma' => 4, 'genera_iva' => 1, 'tipo_servicio' =>'alcantarillado'],
                ['id_tarifa' => 1, 'id_tipo_toma' => 4, 'genera_iva' => 1, 'tipo_servicio' =>'saneamiento'],
            ]
        ];
        return $data[$index++ % count($data)];
    }
}
