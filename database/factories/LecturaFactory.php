<?php

namespace Database\Factories;

use App\Models\CargaTrabajo;
use App\Models\Operador;
use App\Models\Toma;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lectura>
 */
class LecturaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_empleado_lecturista'=> Operador::all()->random()->id,
            'id_toma'=> Toma::all()->random()->id,
            'id_carga_trabajo'=> CargaTrabajo::all()->random()->id,
            'lectura'=>$this->faker->numberBetween(1,100),
        ];
    }
}
