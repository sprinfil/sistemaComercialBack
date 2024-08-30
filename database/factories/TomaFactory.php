<?php

namespace Database\Factories;

use App\Models\GiroComercialCatalogo;
use App\Models\Libro;
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
        static $codigoTomaId = 1;
        static $usuarioId = 0;
        $usuarioId++;

        $latitud = '-110.3'.$this->faker->numerify('#').'761062742';
        $longitud = '24.1'.$this->faker->numerify('#').'5858323185';

        //$numeroPagos = 123; // Ejemplo, reemplaza esto con tu número de pagos real
        $numeroTomas = Toma::all()->count();

        $libro_sel = Libro::inRandomOrder()->first();
        $nombre_libro = $libro_sel->nombre;
        $ruta_sel = $libro_sel->tieneRuta;
        $nombre_ruta = $ruta_sel->nombre;
        
        // Usa una expresión regular para encontrar el número
        preg_match('/\d+/', $nombre_libro, $coincidencias_libro);
        preg_match('/\d+/', $nombre_ruta, $coincidencias_ruta);

        $numero_libro = isset($coincidencias_libro[0]) ? (int)$coincidencias_libro[0] : null;
        $numero_ruta = isset($coincidencias_ruta[0]) ? (int)$coincidencias_ruta[0] : null;
        // Genera el folio como una cadena formateada
        $folio = strtoupper(
            ''.str_pad($numero_ruta, 2, '0', STR_PAD_LEFT).''.str_pad($numero_libro, 2, '0', STR_PAD_LEFT).''.str_pad($libro_sel->countTomas()+1, 3, '0', STR_PAD_LEFT)
        );

        return [
            'id_usuario' => $usuarioId,
            'id_giro_comercial' => GiroComercialCatalogo::inRandomOrder()->first()->id,
            'id_libro' => $libro_sel->id,
            'codigo_toma' => $folio,
            'id_tipo_toma' => $this->faker->randomElement([1,2,3,4]),
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
            'posicion' => new Point($longitud, $latitud),
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
