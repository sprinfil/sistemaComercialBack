<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Caja>
 */
class CajaFactory extends Factory
{
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaApertura = $this->faker->dateTimeBetween('-1 year' , 'now');
        $fechaCierre = $this->faker->dateTimeBetween($fechaApertura , 'now');
        $fondoInicial = $this->faker->numberBetween(2000,2500);
        $fondoFinal = $this->faker->numberBetween(2000,2500);
        return [
            'id_operador'=>$this->faker->numberBetween(1,4),
            'id_caja_catalogo'=>$this->faker->numberBetween(1,100),
            'fondo_inicial'=> $fondoInicial,
            'fondo_final'=> $fondoFinal,
            'fecha_apertura'=> $fechaApertura,
            'fecha_cierre' =>  $fechaCierre,
        ];
    }
}
