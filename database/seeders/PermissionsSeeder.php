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
            //CONCEPTOS
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
            //DESCUENTOS
            [
                'name' => 'VerDescuentos',
                'guard_name' => 'web',
            ],
            [
                'name' => 'CrearDescuento',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EditarDescuento',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EliminarDescuento',
                'guard_name' => 'web',
            ],
            //CONVENIOS
            [
                'name' => 'VerConvenios',
                'guard_name' => 'web',
            ],
            [
                'name' => 'CrearConvenio',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EditarConvenio',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EliminarConvenio',
                'guard_name' => 'web',
            ],
            //AJUSTES
            [
                'name' => 'VerAjustes',
                'guard_name' => 'web',
            ],
            [
                'name' => 'CrearAjuste',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EditarAjuste',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EliminarAjuste',
                'guard_name' => 'web',
            ],
        ]);
    }
}
