<?php

namespace Database\Factories;

use App\Models\Caja;
use App\Models\CorteCaja;
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
        $referencia = null;
        $numeroPagos = Pago::count() + 1;
        $metodoDePago = $this->faker->randomElement(['efectivo','tarjeta_credito','tarjeta_debito','cheque','transferencia','documento']);
        if($metodoDePago!='efectivo')
        {
            $referencia = strtoupper('C'.str_pad($this->faker->randomFloat(2, 0, 9999), 2, '0', STR_PAD_LEFT).'P' . str_pad($numeroPagos, 4, '0', STR_PAD_LEFT));
        }

        return [
            'folio'=>strtoupper('C'.str_pad(1, 2, '0', STR_PAD_LEFT).'P' . str_pad($numeroPagos, 4, '0', STR_PAD_LEFT)),
            'id_caja'=>$this->faker->numberBetween(1,4),
            'id_dueno' => 0,
            'modelo_dueno' => '',
            //'id_corte_caja'=>CorteCaja::all()->random()->id,
            'total_pagado'=>$this->faker->randomFloat(2, 0, 9999),
            'saldo_anterior'=>0,
            'forma_pago'=> $this->faker->randomElement(['efectivo','tarjeta_credito','tarjeta_debito','cheque','transferencia','documento']),
            'fecha_pago'=>now(),
            'estado'=> $this->faker->randomElement(['abonado', 'pendiente', 'cancelado']),
            //"timbrado"=>$this->faker->randomElement(['realizado', 'pendiente', 'cancelado']),
            'referencia'=>$referencia,
        ];
    }
}
