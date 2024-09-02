<?php

namespace Database\Factories;

use App\Models\OrdenesTrabajoCargo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ordenes_trabajo_cargo>
 */
class OrdenesTrabajoCargoFactory extends Factory
{
    protected $model = OrdenesTrabajoCargo::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'id_orden_trabajo_catalogo' => $this->faker->numberBetween(1, 100), // Asumiendo un rango de IDs válidos
            'id_concepto_catalogo' => $this->faker->numberBetween(1, 100), // Asumiendo un rango de IDs válidos
        ];
       
    }
}
