<?php

namespace Database\Factories;

use App\Models\Libro;
use App\Models\Ruta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ruta>
 */
class RutaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $num = 1;
        return [
            'nombre' => 'Ruta ' .$num++,
            'color' => $this->faker->hexColor,
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Ruta $ruta) 
        {
            for ($i = 1; $i < 3; $i++) {
                Libro::factory()->create([
                    'id_ruta'=>$ruta->id,
                    'nombre' => 'Libro ' . $i,
                ]);
            }
        });
    }
}
