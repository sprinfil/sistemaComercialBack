<?php

namespace Database\Factories;

use App\Models\tarifa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Periodo>
 */
class PeriodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'id_ruta'=>$this->faker->numberBetween(1,10),
           'id_tarifa'=>$this->faker->numberBetween(1,10),
           'facturacion_fecha_inicio'=>now(),
           'facturacion_fecha_final'=>now(),
           'lectura_inicio'=>now(),
           'lectura_final'=>now()->addDays(30),
        ];
    }
}
