<?php

namespace Database\Factories;

use App\Models\Cargo;
use App\Models\ConceptoCatalogo;
use App\Models\Contrato;
use App\Models\Factibilidad;
use App\Models\Toma;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contrato>
 */
class ContratoFactory extends Factory
{
    protected $model = Contrato::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $tomaId = 0;
        static $usuarioId = 0;
        $servicio = $this->faker->randomElement(['agua', 'alcantarillado y saneamiento']);

        $tomaId++;
        $usuarioId++;
        $numContratos = Contrato::count();

        // Obtén la toma correspondiente
        $toma = Toma::find($tomaId);

        if ($toma) {
            // Asigna el valor correspondiente a la propiedad
            if ($servicio == 'agua') {
                $toma->c_agua = $numContratos + 1;
            } else {
                $toma->c_agua = $numContratos + 1;
                $toma->c_alc_san = $numContratos + 1;
            }
            
            // Guarda el objeto
            $toma->save();
        }


        $nombrec = 'Juanito'; // default
        // Obtén la toma correspondiente
        $toma = Usuario::find($usuarioId);
        if ($toma) {
            $nombrec = Usuario::find($usuarioId)->nombre.' '.Usuario::find($usuarioId)->apellido_paterno.' '.Usuario::find($usuarioId)->apellido_materno;
        }


        return [
            'id_toma' => $tomaId,
            'id_usuario' => $usuarioId,
            'folio_solicitud' => $this->faker->unique()->regexify('[A-Za-z0-9]{10}'),
            'estatus' => $this->faker->randomElement([
                'pendiente de inspeccion',
                'contrato no factible',
                'inspeccionado',
                'pendiente de pago',
                'contratado',
                'terminado',
                'cancelado',
            ]),
            'nombre_contrato' => $nombrec,
            'clave_catastral' => Toma::find($tomaId)->clave_catastral ?? $this->faker->regexify('[A-Z0-9]{10}'),
            'tipo_toma' => Toma::find($tomaId)->tipo_toma ?? $this->faker->randomElement(['domestica', 'comercial', 'industrial']),
            'servicio_contratado' => $servicio,
            'colonia' => $this->faker->streetName,
            'municipio' => $this->faker->city,
            'localidad' => $this->faker->city,
            'calle' => $this->faker->streetAddress,
            'entre_calle1' => $this->faker->optional()->streetName,
            'entre_calle2' => $this->faker->optional()->streetName,
            'domicilio' => $this->faker->address,
            'diametro_de_la_toma' => $this->faker->randomElement(['1/2 pulgada', '3/4 pulgada', '1 pulgada']),
            'codigo_postal' => $this->faker->postcode,
            'coordenada' => $this->faker->optional()->latitude . ', ' . $this->faker->optional()->longitude,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Contrato $contrato) {
            $contrato_alc = null;
            if ($contrato->servicio_contratado == 'alcantarillado y saneamiento') {
                $contrato_alc = Contrato::factory()->create([
                    'id_toma' => $contrato->id_toma,
                    'id_usuario' => $contrato->id_usuario,
                    'nombre_contrato' => $contrato->nombre_contrato,
                    'clave_catastral'=> $contrato->clave_catastral,
                    'tipo_toma'=> $contrato->tipo_toma,
                    'servicio_contratado' => 'agua',
                ]);
            }
            $estado_pago = 'ninguno';
            $fecha_liquidacion = null;

            if($contrato->estatus == 'pendiente de pago'){
                $estado_pago = 'pendiente';
            } else if($contrato->estatus == 'contratado' || $contrato->estatus == 'terminado'){
                $estado_pago = 'pagado';
                $fecha_liquidacion = now();
            }

            $derechos_conexion = 0;

            if($contrato->tipo_toma == 'domestica'){
                $derechos_conexion = 0.00;
            }else if($contrato->tipo_toma == 'industrial'){
                $derechos_conexion = 500.00;
            }else if($contrato->tipo_toma == 'comercial'){
                $derechos_conexion = 250.00;
            }

            if($contrato->estatus == 'contrato no factible'){
                $factibilidad = Factibilidad::factory()->create([
                    'id_contrato' => $contrato->id,
                    'agua_estado_factible' => 'no_factible',
                    'alc_estado_factible' => 'no_factible',
                    'derechos_conexion' => 0
                ]);

                Cargo::factory()->create([
                    'id_concepto' => 1,
                    'concepto' => 'factibilidad '.$contrato->tipo_toma, 
                    'id_origen' => $factibilidad->id,
                    'modelo_origen' => 'factibilidad',
                    'id_dueño' => $contrato->id_toma,
                    'modelo_dueño' => 'toma',
                    'monto' => 500.00,
                    'estado' => 'pagado',
                    'fecha_cargo' => now(),
                    'fecha_liquidacion' => now(),
                    'deleted_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                if($contrato->estatus != 'pendiente de inspeccion'){
                    $factibilidad = Factibilidad::factory()->create([
                        'id_contrato' => $contrato->id,
                        'agua_estado_factible' => 'factible',
                        'alc_estado_factible' => 'factible',
                        'derechos_conexion' => $derechos_conexion
                    ]);

                    Cargo::factory()->create([
                        'id_concepto' => 1,
                        'concepto' => 'factibilidad '.$contrato->tipo_toma, 
                        'id_origen' => $factibilidad->id,
                        'modelo_origen' => 'factibilidad',
                        'id_dueño' => $contrato->id_toma,
                        'modelo_dueño' => 'toma',
                        'monto' => 351.20,
                        'estado' => 'pagado',
                        'fecha_cargo' => now(),
                        'fecha_liquidacion' => now(),
                        'deleted_at' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Cargo::factory()->create([
                        'id_concepto' => 1,
                        'concepto' => 'derechos de conexion '.$contrato->tipo_toma, 
                        'id_origen' => $factibilidad->id,
                        'modelo_origen' => 'factibilidad',
                        'id_dueño' => $contrato->id_toma,
                        'modelo_dueño' => 'toma',
                        'monto' => $derechos_conexion,
                        'estado' => 'pagado',
                        'fecha_cargo' => now(),
                        'fecha_liquidacion' => now(),
                        'deleted_at' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                } else{
                    Factibilidad::factory()->create([
                        'id_contrato' => $contrato->id,
                        'agua_estado_factible' => 'factible',
                        'alc_estado_factible' => 'factible',
                        'derechos_conexion' => $derechos_conexion
                    ]);
                }
            }

            if ($contrato->estatus == 'pendiente de pago' || $contrato->estatus == 'contratado' || $contrato->estatus == 'terminado') {
                $concepto = ConceptoCatalogo::buscarPorNombre('contrato de '.$contrato->servicio_contratado);
                Cargo::factory()->create([
                    'id_concepto' => $concepto->id ?? 1,
                    'concepto' => $concepto->nombre ?? "", 
                    'id_origen' => $contrato->id,
                    'modelo_origen' => 'contrato',
                    'id_dueño' => $contrato->id_toma,
                    'modelo_dueño' => 'toma',
                    'monto' => $this->faker->randomFloat(2, 0, 9999),
                    'estado' => $estado_pago,
                    'fecha_cargo' => now(),
                    'fecha_liquidacion' => $fecha_liquidacion,
                    'deleted_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

}
