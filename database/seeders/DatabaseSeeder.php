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
use App\Services\SecuenciaService;
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
        $adminOperador = Operador::factory()->create([
            'nombre' => 'admin'
        ]);

        $devOperador = Operador::factory()->create([
            'nombre' => 'dev',
        ]);

        Colonia::factory()->count(10)->create();
        Calle::factory()->count(100)->create();

        $this->call(UsuarioSeeder::class);
        //
        $this->call(GiroComercialSeeder::class);
        $this->call(TipoTomasSeeder::class);
        $this->call(ConceptoCatalogoSeeder::class);
        //
        $this->call(RutaSeeder::class);
        $this->call(LibroSeeder::class);
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
        $this->call(CajaCatalogoSeeder::class);
        $this->call(PagoSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(TipoTomaSeeder::class);
        //
        $this->call(PeriodoSeeder::class);
        $this->call(CargaTrabajoSeeder::class);
        $this->call(LecturaSeeder::class);
        $this->call(AsignacionGeograficaSeeder::class);
        //
        $this->call(OrdenesTrabajoSeeder::class);
        //
    }
}
