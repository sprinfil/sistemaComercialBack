<?php

namespace Database\Factories;

use App\Models\GiroComercialCatalogo;
use App\Models\Libro;
use App\Models\Medidor;
use App\Models\Toma;
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
            'calle' => $this->faker->streetName,
            'entre_calle_1' => $this->faker->streetName,
            'entre_calle_2' => $this->faker->streetName,
            'colonia' => $this->faker->city,
            'codigo_postal' => $this->faker->postcode,
            'numero_casa' => $this->faker->numerify('####'),
            'localidad' => $this->faker->city,
            'diametro_toma' => $this->faker->randomNumber(),
            'calle_notificaciones' => $this->faker->streetName,
            'entre_calle_notificaciones_1' => $this->faker->streetName,
            'entre_calle_notificaciones_2' => $this->faker->streetName,
            'tipo_servicio' => $this->faker->randomElement(['promedio', 'lectura']),
            'c_agua' => null,
            'c_alc' => null,
            'c_san' => null,
            'tipo_contratacion' => $this->faker->randomElement(['normal', 'condicionado', 'desarrollador']),
            'posicion' => null,  // Este valor será sobrescrito al usar create en el LibroFactory
            'deleted_at' => null,
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
        return $this->afterCreating(function (Toma $toma) 
        {
            Medidor::factory()->create([
                'id_toma' => $toma->id
            ]);
        });
    }
}