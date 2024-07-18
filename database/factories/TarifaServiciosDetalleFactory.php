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
            'id_tarifa'=>0,
            'id_tipo_toma'=>0,
            'rango'=>0,
            'agua'=>0,
            'alcantarillado'=>0,
            'saneamiento'=>0
        ];
    }
}
