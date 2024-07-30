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
            'accion' => $this->faker->randomElement(['cargo', 'efectivo', 'cheque']),
            'momento' => $this->faker->randomElement(['al crear', 'al asignar', 'al concluir']),
            'atributo' => $this->faker->randomElement(['estatus', 'tipo_servicio']),
            'valor' => $this->faker->randomElement(['limitada', 'por promedio', 'por lectura']),
        ];
    }
}
