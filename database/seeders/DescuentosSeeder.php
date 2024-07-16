<?php

namespace Database\Seeders;

use App\Models\DescuentoAsociado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DescuentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('descuento_catalogos')->insert([
            [
                'nombre' => 'Descuento de Verano',
                'descripcion' => 'Descuento aplicado durante la temporada de verano',
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Descuento de Invierno',
                'descripcion' => 'Descuento aplicado durante la temporada de invierno',
                'estado' => 'inactivo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Descuento de Primavera',
                'descripcion' => 'Descuento aplicado durante la temporada de primavera',
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Descuento de Otoño',
                'descripcion' => 'Descuento aplicado durante la temporada de otoño',
                'estado' => 'inactivo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DescuentoAsociado::factory()->count(5)->create();
    }
}
