<?php

namespace Database\Seeders;

use App\Models\Operador;
use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'nombre' => 'Miguel Angel',
                'apellido_paterno' => 'Murillo',
                'apellido_materno' => 'Jaimes',
                'nombre_contacto' => 'Miguel Angel',
                'telefono' => '6121595570',
                'curp' => 'MUJM011113HBSRMGA7',
                'rfc' => 'MUJM750605HML',
                'correo' => 'mgamj13@gmail.com',
            ],
            [
                'nombre' => 'Alan Jacob',
                'apellido_paterno' => 'De La Rosa',
                'apellido_materno' => 'Ruiz',
                'nombre_contacto' => 'Alan Jacob',
                'telefono' => '6121710135',
                'curp' => 'RORA001023HBSSZLA5',
                'rfc' => 'RORA840910MN0',
                'correo' => 'kou4_alan@gmail.com',
            ],
        ]);

        Usuario::factory()->count(30)->create();
        Operador::factory()->count(30)->create();
    }
}
