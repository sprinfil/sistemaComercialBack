<?php

namespace Database\Factories;

use App\Models\Consumo;
use App\Models\Periodo;
use App\Models\Toma;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Factura>
 */
class FacturaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_periodo'=> Periodo::all()->random()->id,
            'id_toma'=> Toma::all()->random()->id,
            'id_consumo'=> Consumo::all()->random()->id,
            'monto'=>$this->faker->numberBetween(1,400),
            'fecha'=>now(),
        ];
    }
}
