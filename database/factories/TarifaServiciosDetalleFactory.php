<?php

namespace Database\Factories;

use App\Models\TarifaServiciosDetalle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TarifaServiciosDetalle>
 */
class TarifaServiciosDetalleFactory extends Factory
{
    protected $model = TarifaServiciosDetalle::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_tarifa_servicio' => 1,
            'rango'=> 0,
            'monto' => $this->faker->numberBetween(0,300),
        ];
    }
}
