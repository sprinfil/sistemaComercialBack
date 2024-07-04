<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            [
                'name' => 'Ver Anomalias',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Crear Anomalia',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Editar Anomalia',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Eliminar Anomalia',
                'guard_name' => 'web',
            ],
        ]);
    }
}
