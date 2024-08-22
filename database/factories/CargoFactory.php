<?php

namespace Database\Factories;

use App\Models\Abono;
use App\Models\Cargo;
use App\Models\Pago;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cargo>
 */
class CargoFactory extends Factory
{
    protected $model = Cargo::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_concepto' => 1,
            'nombre' => 'ninguno',
            'id_origen' => $this->faker->numberBetween(1, 100),
            'modelo_origen' => $this->faker->word,
            'id_dueno' => $this->faker->numberBetween(1, 100),
            'modelo_dueno' => $this->faker->word,
            'monto' => $this->faker->randomFloat(2, 0, 9999),
            'iva' => $this->faker->randomFloat(2, 0, 10),
            'estado' => $this->faker->randomElement(['pendiente', 'pagado', 'conveniado', 'cancelado']),
            'id_convenio' => null,
            'fecha_cargo' => now(),
            'fecha_liquidacion' => now()->addDays(1),
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Cargo $cargo) {
            if ($cargo->estado == 'pagado') {
                // origen del abono
                $id_origen = 0;
                $origen_abono = $this->faker->randomElement(['pago']);
                $total_abonado = 0;
                if($origen_abono == 'pago')
                {
                    $total_abonado = $cargo->monto;
                    $pago = Pago::factory()->create([
                        'id_dueno' => $cargo->id_dueno,
                        'modelo_dueno' => $cargo->modelo_dueno,
                        'total_pagado'=>$total_abonado,
                        //'forma_pago'=> $this->faker->randomElement(['tarjeta', 'efectivo', 'cheque']),
                        //'fecha_pago'=>$this->faker->randomFloat(2, 0, 9999),
                        'estado'=> 'abonado',
                    ]);  
                    $id_origen = $pago->id;
                }

                Abono::factory()->create([
                    'id_cargo'=> $cargo->id,
                    'id_origen'=> $id_origen,
                    'modelo_origen'=> $origen_abono,
                    'total_abonado'=>$total_abonado,
                ]); 
            }
        });
    }
}
