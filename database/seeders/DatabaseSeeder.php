<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\AnomaliaSeeder;
use Database\Seeders\ConvenioSeeder;
use Database\Seeders\ConceptoCatalogoSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'test@example.com',
            'password' => '$2y$12$doEXdsTesrTif4re8ES2huh9rWGaUkBCkSupshDOdp1EdXElPYAmq',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'dev',
            'email' => 'dev@example.com',
            'password' => '$2y$12$doEXdsTesrTif4re8ES2huh9rWGaUkBCkSupshDOdp1EdXElPYAmq',
        ]);

        //
        $this->call(UsuarioSeeder::class);
        $this->call(GiroComercialSeeder::class);
        $this->call(TipoTomasSeeder::class);
        $this->call(TomaSeeder::class);
        //
        $this->call(DescuentosSeeder::class);
        // 
        $this->call(AnomaliaSeeder::class);
        $this->call(AjusteSeeder::class);
        $this->call(ConceptoCatalogoSeeder::class);
        $this->call(ConvenioSeeder::class);
        $this->call(ConstanciaSeeder::class);
        $this->call(BonificacionCatalogoSeeder::class);
        //
        $this->call(RolSeeder::class);
        $this->call(PermissionsSeeder::class);
    }
}
