<?php

namespace Database\Factories;

use App\Models\Caja;
use App\Models\corteCaja;
use App\Models\Pago;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pago>
 */
class PagoFactory extends Factory
{
    protected $model = Pago::class;
    /**
     * Define the model's default state.
     * $this->faker->numberBetween(1,4)
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_caja'=>$this->faker->numberBetween(1,4),
            'id_dueno' => 0,
            'modelo_dueno' => '',
            //'id_corte_caja'=>corteCaja::all()->random()->id,
            'total_pagado'=>$this->faker->randomFloat(2, 0, 9999),
            'forma_pago'=> $this->faker->randomElement(['tarjeta', 'efectivo', 'cheque']),
            'fecha_pago'=>now(),
            'estado'=> $this->faker->randomElement(['abonado', 'pendiente', 'cancelado']),
        ];
    }
}
