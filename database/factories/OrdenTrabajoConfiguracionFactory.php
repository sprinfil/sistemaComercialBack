<?php

namespace Database\Factories;

use App\Models\OrdenTrabajoConfiguracion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrdenTrabajoConfiguracion>
 */
class OrdenTrabajoConfiguracionFactory extends Factory
{
    protected $model = OrdenTrabajoConfiguracion::class;
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
            'momento' => $this->faker->randomElement(['generar', 'asignar', 'concluir']),
            'atributo' => '',
            'valor' => '',
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (OrdenTrabajoConfiguracion $configuracion) {
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
