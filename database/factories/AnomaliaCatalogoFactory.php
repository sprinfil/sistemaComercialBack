<?php

namespace Database\Factories;

use App\Models\AnomaliaCatalogo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AnomaliaCatalogo>
 */
class AnomaliaCatalogoFactory extends Factory
{
    protected $model = AnomaliaCatalogo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->word,
            'descripcion' => $this->faker->sentence,
            'estado' => $this->faker->randomElement(['activo', 'inactivo']),
            'facturable' => $this->faker->boolean,
        ];
    }
}
