<?php

namespace Database\Factories;

use App\Models\ConceptoCatalogo;
use App\Models\OrdenTrabajoAccion;
use App\Models\OrdenTrabajoCatalogo;
use App\Models\OrdenTrabajoConfiguracion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrdenTrabajoCatalogo>
 */
class OrdenTrabajoCatalogoFactory extends Factory
{
    protected $model = OrdenTrabajoCatalogo::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "nombre",
            "vigencias",
            "momentoCargo",
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (OrdenTrabajoCatalogo $orden_de_trabajo) {
            $concepto = ConceptoCatalogo::buscarPorNombre($orden_de_trabajo->nombre);
            $configuracion = OrdenTrabajoAccion::factory()->create([
                'id_orden_trabajo_catalogo' => $orden_de_trabajo->id,
                'id_concepto_catalogo' => $concepto->id,
            ]); 
        });
    }
}
