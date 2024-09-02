<?php

namespace Database\Factories;

use App\Models\Libro;
use App\Models\Ruta;
use App\Models\Toma;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Libro>
 */
class LibroFactory extends Factory
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
            'id_ruta'=>Ruta::all()->random()->id,
            'nombre' => 'Libro ' . $num++,
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Libro $libro) 
        {
            for ($i = 0; $i < 30; $i++) {
                Toma::factory()->count(1)->withAdditionalParam($i+1)->create(['id_libro' => $libro->id]);
            }
        });
    }
    /*
    Toma::factory()->count(30)->withAdditionalParam('XYZ')->create(['id_libro' => $libro->id]);

    */
}
