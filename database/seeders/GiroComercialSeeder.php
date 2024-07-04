<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GiroComercialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('giro_comercial_catalogos')->insert([
            [
                'nombre' => 'Escuelas',
                'descripcion' => 'Escuelas',
            ],
            [
                'nombre' => 'Bancos',
                'descripcion' => 'Bancos',
            ],
            [
                'nombre' => 'Empresas constructoras',
                'descripcion' => 'Empresas constructoras',
            ],
            [
                'nombre' => 'Cines',
                'descripcion' => 'Cines',
            ],
            [
                'nombre' => 'Concesionarios de automóviles',
                'descripcion' => 'Concesionarios de automóviles',
            ],
            [
                'nombre' => 'Hoteles',
                'descripcion' => 'Hoteles',
            ],
        ]);
    }
}
