<?php

namespace Database\Factories;

use App\Models\OrdenTrabajoAccion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrdenTrabajoConfiguracion>
 */
class OrdenTrabajoAccionFactory extends Factory
{
    protected $model = OrdenTrabajoAccion::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_orden_trabajo_catalogo' => $this->faker->numberBetween(1, 100), // Asumiendo que existe un rango de IDs válidos
            'accion' => $this->faker->randomElement(['registrar', 'modificar', 'quitar']),
            'modelo' => $this->faker->word,
            'campo' => $this->faker->optional()->word, // Puede ser nulo o tener un valor
        ];
    }
}
