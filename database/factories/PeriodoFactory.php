<?php

namespace Database\Factories;

use App\Models\Libro;
use App\Models\Tarifa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Periodo>
 */
class PeriodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'id_ruta'=>Libro::all()->random()->id,
           'id_tarifa'=>Tarifa::all()->random()->id,
           'facturacion_fecha_inicio'=>now(),
           'facturacion_fecha_final'=>now(),
           'lectura_inicio'=>now(),
           'lectura_final'=>now()->addDays(30),
        ];
    }
}
