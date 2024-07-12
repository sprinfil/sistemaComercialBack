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
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Bancos',
                'descripcion' => 'Bancos',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Empresas constructoras',
                'descripcion' => 'Empresas constructoras',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Cines',
                'descripcion' => 'Cines',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Concesionarios de automóviles',
                'descripcion' => 'Concesionarios de automóviles',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Hoteles',
                'descripcion' => 'Hoteles',
                'estado' => 'activo',
            ],
        ]);
    }
}
