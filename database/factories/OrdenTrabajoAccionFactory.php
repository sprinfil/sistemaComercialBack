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
            'id_orden_trabajo_catalogo' => 0,
            'accion' => $this->faker->randomElement(['registrar','modificar','quitar']),
            'modelo' => $this->faker->randomElement(['usuario', 'toma', 'lectura', 'medidor', 'consumo']),
            'opcional' => 0,
            'id_orden_trabajo_acc_encadena' => 0,
            'id_orden_trabajo_acc_alterna' => 0,
        ];
    }
}
