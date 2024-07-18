<?php

namespace Database\Factories;

use App\Models\CotizacionDetalle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CotizacionDetalle>
 */
class CotizacionDetalleFactory extends Factory
{
    protected $model = CotizacionDetalle::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_cotizacion'=>1,
            'id_sector'=>$this->faker->numberBetween(1, 10),
            'nombre_concepto'=>"",
            'monto'=>0.00
        ];
    }
}
