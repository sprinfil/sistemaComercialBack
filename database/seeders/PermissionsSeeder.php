<?php

namespace Database\Seeders;

use App\Models\User;
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
        $user = User::find(1);
        $user->assignRole('Admin');
        DB::table('permissions')->insert([
            //ANOMALIAS
            [
                'name' => 'VerAnomalias',
                'guard_name' => 'web',
            ],
            [
                'name' => 'CrearAnomalia',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EditarAnomalia',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EliminarAnomalia',
                'guard_name' => 'web',
            ],
            //GIROS COMERCIALES
            [
                'name' => 'VerGirosComerciales',
                'guard_name' => 'web',
            ],
            [
                'name' => 'CrearGiroComercial',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EditarGiroComercial',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EliminarGircoComercial',
                'guard_name' => 'web',
            ],
            //GIROS CONCEPTOS
            [
                'name' => 'VerConceptos',
                'guard_name' => 'web',
            ],
            [
                'name' => 'CrearConcepto',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EditarConcepto',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EliminarConcepto',
                'guard_name' => 'web',
            ],
        ]);
    }
}
