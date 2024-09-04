<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoTomasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_tomas')->insert([
            [
                'nombre' => 'Domestica',
                'descripcion' => 'ninguna',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Comercial',
                'descripcion' => 'ninguna',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Industrial',
                'descripcion' => 'ninguna',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Escuelas',
                'descripcion' => 'ninguna',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Usuarios',
                'descripcion' => 'ninguna',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
