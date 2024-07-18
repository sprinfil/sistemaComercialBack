<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Abono>
 */
class AbonoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_cargo'=> $this->faker->numberBetween(1, 100),
            'id_origen'=> $this->faker->numberBetween(1, 100),
            'modelo_origen'=> $this->faker->randomElement(['contrato', 'convenio', 'facturacion']),
            'total_abonado'=>$this->faker->randomFloat(2, 0, 9999),
        ];
    }
}
