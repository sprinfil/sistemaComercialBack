<?php

namespace Database\Factories;

use App\Models\Lectura;
use App\Models\Toma;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Consumo>
 */
class ConsumoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_toma'=> Toma::all()->random()->id,
            'id_lectura_anterior'=> Lectura::all()->random()->id,
            'id_lectura_actual'=> Lectura::all()->random()->id,
            'consumo'=>$this->faker->numberBetween(1,100),
        ];
    }
}
