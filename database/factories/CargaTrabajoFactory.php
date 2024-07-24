<?php

namespace Database\Factories;

use App\Models\Operador;
use App\Models\Periodo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CargaTrabajo>
 */
class CargaTrabajoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_libro'=> $this->faker->numberBetween(1,20),
            'id_lectura'=> $this->faker->numberBetween(1,20), //lectura no tiene seeder
            'id_operador_encargado'=> Operador::all()->random()->id,
            'id_periodo'=> Periodo::all()->random()->id,
            'id_operador_asigno'=> Operador::all()->random()->id,
            'estado'=>$this->faker->randomElement(['no asignada' , 'en proceso' , 'concluida' , 'cancelada']),
            'fecha_concluida'=>now(),
            'fecha_asignacion'=>now(),
            'tipo_carga'=>$this->faker->randomElement(['lectura' , 'facturacion' , 'facturacion en sitio' ]),
        ];
    }
}
