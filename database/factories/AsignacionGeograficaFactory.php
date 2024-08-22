<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AsignacionGeografica>
 */
class AsignacionGeograficaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_modelo'=>$this->faker->numberBetween(1, 100),
            'modelo' => $this->faker->randomElement(['ruta', 'toma', 'libro']),
            'estatus' => $this->faker->randomElement(['activo', 'inactivo'])
        ];
    }
}
