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
                'nombre' => 'domestica',
                'descripcion' => 'ninguna',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'comercial',
                'descripcion' => 'ninguna',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'industrial',
                'descripcion' => 'ninguna',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'escuelas',
                'descripcion' => 'ninguna',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
