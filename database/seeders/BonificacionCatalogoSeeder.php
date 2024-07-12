<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BonificacionCatalogoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('catalogo_bonificaciones')->insert([
            [
                'nombre' => '60 y mas',
                'descripcion' => 'Aqui se describe el bono',
                'estado' => 'activo',
                'vigencia' => '2024/07/11',
            ],
          
        ]);
        //
    }
}
