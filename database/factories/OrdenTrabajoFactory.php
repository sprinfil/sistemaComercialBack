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
            'id_toma' => \App\Models\Toma::pluck('id')->random(), // Obtiene un ID existente de 'tomas'
            'id_empleado_genero' => \App\Models\Operador::pluck('id')->random(), // Obtiene un ID existente de 'operadores' (empleados)
            'id_empleado_asigno' => \App\Models\Operador::pluck('id')->random(), // Obtiene un ID existente de 'operadores' o puede ser nulo
            'id_empleado_encargado' => $this->faker->optional()->randomElement(\App\Models\Operador::pluck('id')->toArray()), // Puede ser nulo o tener un ID válido de 'operadores'
            'id_orden_trabajo_catalogo' => \App\Models\OrdenTrabajoCatalogo::pluck('id')->random(), // Obtiene un ID existente de 'ot catalogo'
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
