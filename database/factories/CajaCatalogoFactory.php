<?php

namespace Database\Factories;

use App\Models\Caja;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CajaCatalogo>
 */
class CajaCatalogoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_cuenta_contable' => $this->faker->numberBetween(21, 37),
            'nombre_caja'=>$this->faker->randomElement(['Zona Urbana','El Pescadero' ,'Los Barriles' ,'El Sargento' , 'Agua Amarga' ,'Meliton Albañez' , 'San Pedro' , 'Reforma Agraria', 'El Triunfo' ,'Albaro Obregon' , 'Elias Calles', 'Los Planes' , 'San Bartolo','Juan Dominguez Cota' , 'San Antonio','Conquista Agraria' , 'El Carrizal','El Cardonal', 'Las Pocitas','La trinidad' , 'La Ventana' ]),
            'hora_apertura'=> $this->faker->dateTimeBetween('-1 year', 'now'),
            'hora_cierre' =>  $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
