<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->assignRole('Admin');
        }

        $user = User::find(2);
        $user->assignRole('Developer');
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
            //CONSTANCIA
            [
                'name' => 'VerConstancias',
                'guard_name' => 'web',
            ],
            [
                'name' => 'CrearConstancia',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EditarConstancia',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EliminarConstancia',
                'guard_name' => 'web',
            ],
            //BONIFICACION
            [
                'name' => 'VerBonificaciones',
                'guard_name' => 'web',
            ],
            [
                'name' => 'CrearBonificacion',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EditarBonificacion',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EliminarBonificacion',
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
            //TIPO DE TOMA
            [
                'name' => 'VerTiposDeToma',
                'guard_name' => 'web',
            ],
            [
                'name' => 'CrearTipoDeToma',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EditarTipoDeToma',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EliminarTipoDeTomas',
                'guard_name' => 'web',
            ],
            //OPERADORES DEL SISTEMA
            [
                'name' => 'VerOperadores',
                'guard_name' => 'web',
            ],
            [
                'name' => 'CrearOperador',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EditarOperador',
                'guard_name' => 'web',
            ],
            [
                'name' => 'EliminarOperador',
                'guard_name' => 'web',
            ],

            //CONFIGURACION
            [
                'name' => 'VerConfiguraciones',
                'guard_name' => 'web',
            ],
            [
                'name' => 'VerCatalogos',
                'guard_name' => 'web',
            ],
            [
                'name' => 'VerOperadoresSistema',
                'guard_name' => 'web',
            ],
        ]);
    }
}
