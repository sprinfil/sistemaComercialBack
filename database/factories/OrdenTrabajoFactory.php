<?php

namespace Database\Factories;

use App\Models\OrdenTrabajo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrdenTrabajo>
 */
class OrdenTrabajoFactory extends Factory
{
    protected $model = OrdenTrabajo::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_toma' => $this->faker->numberBetween(1, 100), // Asumiendo un rango de IDs válidos
            'id_empleado_genero' => $this->faker->numberBetween(1, 100), // Asumiendo un rango de IDs válidos
            'id_empleado_asigno' => $this->faker->optional()->numberBetween(1, 100), // Puede ser nulo o tener un ID válido
            'id_empleado_encargado' => $this->faker->optional()->numberBetween(1, 100), // Puede ser nulo o tener un ID válido
            'id_orden_trabajo_catalogo' => $this->faker->numberBetween(1, 100), // Asumiendo un rango de IDs válidos
            'estado' => $this->faker->randomElement(['No asignada', 'Concluida', 'En proceso', 'Cancelada']),
            'fecha_finalizada' => $this->faker->optional()->date(),
            'fecha_vigencia' => $this->faker->optional()->date(),
            'obervaciones' => $this->faker->optional()->sentence,
            'evidencia' => $this->faker->optional()->sentence,
            'material_utilizado' => $this->faker->optional()->word,
            'posicion_OT' => null,
            'genera_OT_encadenadas' => $this->faker->boolean,
        ];
    }
}
