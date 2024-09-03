<?php

namespace Database\Factories;

use App\Models\Medidor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medidor>
 */
class MedidorFactory extends Factory
{
    protected $model = Medidor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $id_toma = 0;

        return [
            'id_toma' => $id_toma++,
            'numero_serie' => Str::random(10),
            'marca' => $this->faker->company,
            'diametro' => $this->faker->randomElement(['20mm', '25mm', '30mm']),
            'tipo' => $this->faker->word,
            'estatus' => $this->faker->randomElement(['activo','inactivo']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
