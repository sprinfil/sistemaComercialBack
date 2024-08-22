<?php

namespace Database\Factories;

use App\Models\Caja;
use App\Models\Operador;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OperadorAsignado>
 */
class OperadorAsignadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'id_operador'=>Operador::all()->random()->id,
           'id_caja_catalogo'=>Caja::all()->random()->id,
        ];
    }
}
