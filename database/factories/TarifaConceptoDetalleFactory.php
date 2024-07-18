<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TarifaConceptoDetalle>
 */
class TarifaConceptoDetalleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_tarifa' => 0,
            'id_tipo_toma' => 0,
            'id_concepto' => 0,
            'monto' => 100
        ];
    }
}
