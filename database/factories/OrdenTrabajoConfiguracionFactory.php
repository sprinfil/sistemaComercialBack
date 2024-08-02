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
            'id_concepto_catalogo' => '',
            'accion' => $this->faker->randomElement(['generar', 'modificar', 'quitar']),
            'modelo' => $this->faker->randomElement(['generar', 'asignar', 'concluir']),
            'opcional' => '',
            'id_orden_trabajo_acc_encadena' => '',
            'id_orden_trabajo_acc_alterna' => '',
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (OrdenTrabajoAccion $configuracion) {
            if (strpos($configuracion->nombre, "reconexion") !== false) {
                $configuracion->atributo = $this->faker->randomElement(['estatus']);
                $configuracion->valor = $this->faker->randomElement(['limitada','activa']);
                $configuracion->save();
            } else if(strpos($configuracion->nombre, "recuperación") !== false) {
                $configuracion->atributo = $this->faker->randomElement(['tipo_servicio']);
                $configuracion->valor = $this->faker->randomElement(['promedio','lectura']);
                $configuracion->save();
            }
        });
    }
}
