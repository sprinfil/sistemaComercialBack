<?php

namespace Database\Factories;

use App\Models\Cargo;
use App\Models\ConceptoCatalogo;
use App\Models\OrdenesTrabajoCargo;
use App\Models\OrdenesTrabajoEncadenada;
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
            'nombre' => $this->faker->word,
            'servicio' => $this->faker->randomElement(['CONSUMO DE AGUA POTABLE', 'SERV. ALCANTARILLADO', 'TRAT. Y SANEAMIENTO', 'OTRO']),
            'descripcion' => $this->faker->sentence,
            'vigencias' => $this->faker->numberBetween(1, 10),
            'momento_cargo' => $this->faker->randomElement(['generar', 'asignar', 'concluir', 'No genera']),
            'genera_masiva' => $this->faker->boolean,
            'asigna_masiva' => $this->faker->boolean,
            'cancela_masiva' => $this->faker->boolean,
            'cierra_masiva' => $this->faker->boolean,
            'publico_general' => $this->faker->boolean,
            'limite_ordenes' => $this->faker->numberBetween(1, 100),
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
            // Crear cargos asociados
            OrdenesTrabajoCargo::factory()->count(1)->create([
                'id_orden_trabajo_catalogo' => $orden_de_trabajo->id,
            ]);

            if($orden_de_trabajo->nombre == 'Limitación')
            {
                OrdenTrabajoAccion::factory()->create([
                    'id_orden_trabajo_catalogo' => $orden_de_trabajo->id,
                    'accion' => 'modificar',
                    'modelo' => 'toma',
                    'campo' => 'estatus',
                    'valor' => 'limitado'
                ]);

                // Cargo::factory()->create([
                //     'id_concepto' => 146,
                //     'nombre' => "Facturacion " . $factura->periodo->nombre,
                //     'id_origen' => $factura->id,
                //     'modelo_origen' => 'facturacion',
                //     'id_dueno' => $factura->id_toma,
                //     'modelo_dueno' => 'toma',
                //     'monto' => $factura->monto,
                //     'iva' => (0.16 * $factura->monto),
                //     'estado' => 'pendiente',
                //     'fecha_cargo' => Periodo::find($factura->id_periodo)->periodo,
                //     'fecha_liquidacion' => null, //$fecha_liquidacion,
                //     'deleted_at' => null,
                //     'created_at' => now(),
                //     'updated_at' => now(),
                // ]);

                OrdenesTrabajoEncadenada::factory()->count(1)->create([
                    'id_OT_Catalogo_padre' => $orden_de_trabajo->id,
                    'id_OT_Catalogo_encadenada' => 3//$this->faker->numberBetween(1, 100),
                ]);
            }
            else if($orden_de_trabajo->nombre == 'Insp consumo elevado')
            {
                // OrdenTrabajoAccion::factory()->create([
                //     'id_orden_trabajo_catalogo' => $orden_de_trabajo->id,
                //     'accion' => 'registrar',
                //     'modelo' => 'medidores',
                //     'campo' => '',
                //     'valor' => ''
                // ]);
            }
            else if($orden_de_trabajo->nombre == 'Instalación de medidores')
            {
                OrdenTrabajoAccion::factory()->create([
                    'id_orden_trabajo_catalogo' => $orden_de_trabajo->id,
                    'accion' => 'registrar',
                    'modelo' => 'medidores',
                    'campo' => '',
                    'valor' => ''
                ]);
            } else{
                // Crear acciones asociadas
                /*OrdenTrabajoAccion::factory()->count(1)->create([
                    'id_orden_trabajo_catalogo' => $orden_de_trabajo->id,
                ]);*/
            }
            
            // Crear encadenadas asociadas
            //OrdenesTrabajoEncadenada::factory()->count(1)->create([
            //    'id_OT_Catalogo_padre' => $orden_de_trabajo->id,
            //]);
        });
    }
}