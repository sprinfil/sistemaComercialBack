<?php

namespace Database\Factories;

use App\Models\Caja;
use App\Models\Pago;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\corteCaja>
 */
class corteCajaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //Asociar el id de la caja con las cajas existentes de la tabla de cajas
            'id_caja'=>Caja::all()->random()->id,
            'id_pago'=>Pago::all()->random()->id,
            'saldo_real'=>$this->faker->randomFloat(2, 0 , 300),
            'saldo_contable'=>$this->faker->randomFloat(2, 0, 300),
            'discrepancia'=>$this->faker->randomElement(['si' ,'no']),
            'discrepancia_monto'=>$this->faker->randomFloat(2, 0, 300),
            'periodo'=>now(),
            'moneda_extranjera'=>$this->faker->randomElement(['MXN','USD']),
            'moneda_nacional'=>$this->faker->randomElement(['MXN','USD']),
            //Validar si se requiere calcular la cantidad de billetes con base al monto real????
            'cantidad_billete_20'=>$this->faker->numberBetween(0,5),
            'cantidad_billete_50'=>$this->faker->numberBetween(0,5),
            'cantidad_billete_100'=>$this->faker->numberBetween(0,5),
            'cantidad_billete_200'=>$this->faker->numberBetween(0,5),
            'cantidad_billete_500'=>$this->faker->numberBetween(0,5),
            'cantidad_billete_1000'=>$this->faker->numberBetween(0,5),

            'cantidad_moneda_1'=>$this->faker->numberBetween(0,5),
            'cantidad_moneda_2'=>$this->faker->numberBetween(0,5),
            'cantidad_moneda_5'=>$this->faker->numberBetween(0,5),
            'cantidad_moneda_10'=>$this->faker->numberBetween(0,5),
            'cantidad_moneda_20'=>$this->faker->numberBetween(0,5),

            'cantidad_centavo_10'=>$this->faker->numberBetween(0,5),
            'cantidad_centavo_20'=>$this->faker->numberBetween(0,5),
            'cantidad_centavo_50'=>$this->faker->numberBetween(0,5),

            'cantidad_billete_dolar_1'=>$this->faker->numberBetween(0,5),
            'cantidad_billete_dolar_2'=>$this->faker->numberBetween(0,5),
            'cantidad_billete_dolar_5'=>$this->faker->numberBetween(0,5),
            'cantidad_billete_dolar_10'=>$this->faker->numberBetween(0,5),
            'cantidad_billete_dolar_20'=>$this->faker->numberBetween(0,5),
            'cantidad_billete_dolar_50'=>$this->faker->numberBetween(0,5),
            'cantidad_billete_dolar_100'=>$this->faker->numberBetween(0,5),

        ];
    }
}
