<?php

namespace Database\Factories;

use App\Models\GiroComercialCatalogo;
use App\Models\Toma;
use Illuminate\Database\Eloquent\Factories\Factory;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Toma>
 */
class TomaFactory extends Factory
{
    protected $model = Toma::class;

    public function definition()
    {
        static $codigoTomaId = 0;
        static $usuarioId = 0;
        $usuarioId++;

        $latitud = '-110.3'.$this->faker->numerify('#').'761062742';
        $longitud = '24.1'.$this->faker->numerify('#').'5858323185';

        return [
            'id_usuario' => $usuarioId,
            'id_giro_comercial' => GiroComercialCatalogo::inRandomOrder()->first()->id,
            'id_libro' => $this->faker->numberBetween(21, 37),
            'id_codigo_toma' => $codigoTomaId++,
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
            'tipo_toma' => $this->faker->randomElement(['1','2','3','4']),
            'c_agua' => null,
            'c_alc' => null,
            'c_san' => null,
            'tipo_contratacion' => $this->faker->randomElement(['normal', 'condicionado', 'desarrollador']),
            'posicion' => new Point($longitud, $latitud),
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
