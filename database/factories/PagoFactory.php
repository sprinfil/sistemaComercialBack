<?php

namespace Database\Factories;

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
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_caja'=>0,
            'id_corte_caja'=>0,
            'total_pagado'=>$this->faker->randomFloat(2, 0, 9999),
            'forma_pago'=> $this->faker->randomElement(['tarjeta', 'efectivo', 'cheque']),
            'fecha_pago'=>now(),
            'estado'=> $this->faker->randomElement(['abonado', 'pendiente', 'cancelado']),
        ];
    }
}
