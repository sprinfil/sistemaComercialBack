<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Calle;
use App\Models\Colonia;
use App\Models\CorteCaja;
use App\Models\Cotizacion;
use App\Models\Factura;
use App\Models\Toma;
use App\Models\Libro;
use App\Models\Operador;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Database\Seeders\AnomaliaSeeder;
use Database\Seeders\ConvenioSeeder;
use Database\Seeders\ConceptoCatalogoSeeder;
use MatanYadaev\EloquentSpatial\Objects\Point;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //
        //$faker = FakerFactory::create();
        // Crear el usuario 'admin' y su operador asociado
        /*$adminUser = User::factory()->create([
            'name' => 'admin',
            'email' => 'test@example.com',
            'password' => '$2y$12$doEXdsTesrTif4re8ES2huh9rWGaUkBCkSupshDOdp1EdXElPYAmq',
        ]);*/

        $adminOperador = Operador::factory()->create([
            'nombre' => 'admin'
        ]);

        // Crear el usuario 'dev' y su operador asociado
        /*$devUser = User::factory()->create([
            'name' => 'dev',
        ]);*/

        $devOperador = Operador::factory()->create([
            'nombre' => 'dev',
        ]);
        //
        Colonia::factory()->count(10)->create();
        // Calles
        Calle::factory()->count(100)->create();

        //
        $this->call(UsuarioSeeder::class);
        //
        $this->call(GiroComercialSeeder::class);
        $this->call(TipoTomasSeeder::class);
        $this->call(ConceptoCatalogoSeeder::class);
        //
        $this->call(RutaSeeder::class);
        $this->call(LibroSeeder::class);
        //
        $libros = Libro::all();
        foreach ($libros as $libro) {
            Usuario::factory()->count(3)->create()->each(function ($usuario) use ($libro) {
                for ($i = 0; $i < rand(1, 3); $i++) {
                    // Generar latitud y longitud
                    $latitud = '-110.3' . (string)rand(0, 3) . str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
                    $longitud = '24.1' . (string)rand(237, 455);

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
                        '' . str_pad($numero_ruta, 2, '0', STR_PAD_LEFT) . '' . str_pad($numero_libro, 2, '0', STR_PAD_LEFT) . '' . str_pad($libro->countTomas() + 1, 3, '0', STR_PAD_LEFT)
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

            $tomasDentroDelPoligono = Toma::whereWithin('posicion', $libro->polygon)->get();
            foreach ($tomasDentroDelPoligono as $toma) {
                $toma->id_libro = $libro->id;
                $toma->save();
            }
        }
        //
        $this->call(DescuentosSeeder::class);
        // 
        $this->call(AnomaliaSeeder::class);
        $this->call(AjusteSeeder::class);
        //
        $this->call(ConvenioSeeder::class);
        $this->call(ConstanciaSeeder::class);
        $this->call(BonificacionCatalogoSeeder::class);
        //
        //$this->call(CajaSeeder::class);
        //$this->call(OperadorAsignadoSeeder::class);
        $this->call(CajaCatalogoSeeder::class);
        $this->call(PagoSeeder::class);
        //$this->call(CorteCajaSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(TipoTomaSeeder::class);
        //
        $this->call(PeriodoSeeder::class);
        $this->call(CargaTrabajoSeeder::class);
        $this->call(LecturaSeeder::class);
        //$this->call(ConsumoSeeder::class);
        //$this->call(FacturaSeeder::class);
        $this->call(AsignacionGeograficaSeeder::class);
        //
        $this->call(OrdenesTrabajoSeeder::class);
        //
    }
}
