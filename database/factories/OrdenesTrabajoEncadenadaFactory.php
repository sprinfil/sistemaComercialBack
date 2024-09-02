<?php

namespace Database\Factories;

use App\Models\OrdenesTrabajoEncadenada;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ordenes_trabajo_encadenada>
 */
class OrdenesTrabajoEncadenadaFactory extends Factory
{
    protected $model = OrdenesTrabajoEncadenada::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_OT_Catalogo_padre' => $this->faker->numberBetween(1, 100), // Asumiendo un rango de IDs válidos
            'id_OT_Catalogo_encadenada' => $this->faker->numberBetween(1, 100), // Asumiendo un rango de IDs válidos
        ];
    }
}
