<?php

namespace Database\Factories;

use App\Models\Contrato;
use App\Models\Libro;
use App\Models\Ruta;
use App\Models\Toma;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Libro>
 */
class LibroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $num = 1;
        return [
            'id_ruta'=>Ruta::all()->random()->id,
            'nombre' => 'Libro ' . $num++,
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Libro $libro) 
        {
            Usuario::factory()->count(30)->create()->each(function ($usuario) use ($libro) {
                for ($i = 0; $i < rand(1, 3); $i++) {
                    // Generar latitud y longitud
                    $latitud = '-110.3' .(string)$this->faker->numberBetween(0,3) .$this->faker->numerify('##');
                    $longitud = '24.1'.(string)$this->faker->numberBetween(237,455);
    
                    // Obtener nombre del libro y la ruta
                    $nombre_libro = $libro->nombre;
                    $ruta_sel = $libro->tieneRuta;
                    $nombre_ruta = $ruta_sel->nombre;
    
                    // Usar una expresión regular para encontrar los números
                    preg_match('/\d+/', $nombre_libro, $coincidencias_libro);
                    preg_match('/\d+/', $nombre_ruta, $coincidencias_ruta);
    
                    $numero_libro = isset($coincidencias_libro[0]) ? (int)$coincidencias_libro[0] : null;
                    $numero_ruta = isset($coincidencias_ruta[0]) ? (int)$coincidencias_ruta[0] : null;
    
                    // Generar el folio
                    $folio = strtoupper(
                        ''.str_pad($numero_ruta, 2, '0', STR_PAD_LEFT).''.str_pad($numero_libro, 2, '0', STR_PAD_LEFT).''.str_pad($libro->countTomas()+1, 3, '0', STR_PAD_LEFT)
                    );

                    // Crear la toma con los datos adicionales
                    $toma = Toma::factory()
                        ->create([
                            'id_libro' => $libro->id,
                            'id_usuario' => $usuario->id,
                            'codigo_toma' => $folio,
                            'posicion' => new Point($longitud, $latitud),
                        ]);
                }
            });
        });
    }
}