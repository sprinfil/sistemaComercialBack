<?php

namespace Database\Factories;

use App\Models\Calle;
use App\Models\Colonia;
use App\Models\Contrato;
use App\Models\GiroComercialCatalogo;
use App\Models\Libro;
use App\Models\Medidor;
use App\Models\Toma;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Toma>
 */
class TomaFactory extends Factory
{
    protected $model = Toma::class;

    // Propiedad para el parámetro adicional
    protected $additionalParam;

    // Método para establecer el parámetro adicional
    public function withAdditionalParam($param)
    {
        $this->additionalParam = $param;
        return $this;
    }

    public function definition()
    {
        return [
            'id_usuario' => null,  // Este valor será sobrescrito al usar create en el LibroFactory
            'id_giro_comercial' => GiroComercialCatalogo::inRandomOrder()->first()->id,
            'id_libro' => null,  // Este valor también será sobrescrito al usar create en el LibroFactory
            'codigo_toma' => null,  // Este valor será sobrescrito al usar create en el LibroFactory
            'id_tipo_toma' => $this->faker->randomElement([1, 2, 3, 4]),
            'clave_catastral' => $this->faker->regexify('[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}'),
            'estatus' => $this->faker->randomElement([
                'pendiente confirmación inspección',
                'pendiente de inspeccion',
                'pendiente de instalacion',
                'activa',
                'baja definitiva',
                'baja temporal',
                'en proceso'
            ]),
            'calle' => $this->faker->numberBetween(1,100),
            'entre_calle_1' =>$this->faker->optional()->numberBetween(1,100),
            'entre_calle_2' => $this->faker->optional()->numberBetween(1,100),
            'colonia' => $this->faker->numberBetween(1,10),
            'codigo_postal' => $this->faker->postcode,
            'numero_casa' => $this->faker->numerify('####'),
            'localidad' => $this->faker->randomElement(['La Paz','Todos santos','Chametla','El Centenario','El Pescadero','Colonia Calafia','El Sargento','El Carrizal','Agua Amarga','Los Barriles','Buena Vista','San Bartolo','San Pedro','San Juan de los Planes','La Matanza','Puerto Chale']),
            'diametro_toma' => $this->faker->randomElement([' 1/2 pulgada','1/4 pulgada','1 pulgada','2 pulgadas','1/8 pulgada']),
            'direccion_notificacion' => $this->faker->numberBetween(1,100),
            'tipo_servicio' => $this->faker->randomElement(['promedio', 'lectura']),
            'c_agua' => null,
            'c_alc' => null,
            'c_san' => null,
            'tipo_contratacion' => $this->faker->randomElement(['normal']), //, 'condicionado', 'pre-contrato '
            'posicion' => $this->faker->optional()->latitude . ', ' . $this->faker->optional()->longitude ,  // Este valor será sobrescrito al usar create en el LibroFactory
            'deleted_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Toma $toma) 
        {
            Medidor::factory()->create([
                'id_toma' => $toma->id
            ]);

            // Crear el contrato para la toma recién creada y pasarle los datos
            $servicio = $this->faker->randomElement(['agua', 'alcantarillado y saneamiento']);

            $usuario = Usuario::find($toma->id_usuario);

            $contrato = Contrato::factory()->create([
                'id_toma' => $toma->id,
                'id_usuario' => $usuario->id,
                'servicio_contratado' => $servicio,
                'clave_catastral' => $toma->clave_catastral,
                'tipo_toma' => $toma->id_tipo_toma,
                'coordenada' => $toma->posicion->latitude . ', ' . $toma->posicion->longitude,
                'nombre_contrato' => $usuario->nombre . ' ' . $usuario->apellido_paterno . ' ' . $usuario->apellido_materno,
                'colonia' => $toma->colonia,
                'calle' => $toma->calle,
                'municipio' => $this->faker->randomElement(['La Paz', 'Los cabos','Comondu']),
                'localidad' => $toma->localidad,
                'num_casa' => $this->faker->numerify('###'),
            ]);

            $toma_actualizada = Toma::find($toma->id);
            if($servicio == 'agua'){
                $toma_actualizada->c_agua = $contrato->id;
            } 
            else if($servicio == 'alcantarillado y saneamiento'){
                $toma_actualizada->c_agua = $contrato->id;
                $toma_actualizada->c_alc = $contrato->id;
                $toma_actualizada->c_san = $contrato->id;
            }
            $toma_actualizada->save();
        });
    }
}