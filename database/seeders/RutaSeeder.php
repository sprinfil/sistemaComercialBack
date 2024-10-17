<?php

namespace Database\Seeders;

use App\Models\Ruta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RutaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("INSERT INTO `rutas` (`id`, `nombre`, `color`, `deleted_at`, `created_at`, `updated_at`) VALUES
	(35, 'R01', NULL, NULL, '2024-10-15 23:05:47', '2024-10-15 23:05:47'),
	(36, 'R02', NULL, NULL, '2024-10-15 23:05:47', '2024-10-15 23:05:47'),
	(37, 'R03', NULL, NULL, '2024-10-15 23:05:47', '2024-10-15 23:05:47'),
	(38, 'R04', NULL, NULL, '2024-10-15 23:05:47', '2024-10-15 23:05:47'),
	(39, 'R05', NULL, NULL, '2024-10-15 23:05:48', '2024-10-15 23:05:48'),
	(40, 'R06', NULL, NULL, '2024-10-15 23:05:48', '2024-10-15 23:05:48'),
	(41, 'R07', NULL, NULL, '2024-10-15 23:05:48', '2024-10-15 23:05:48'),
	(42, 'R08', NULL, NULL, '2024-10-15 23:05:48', '2024-10-15 23:05:48'),
	(43, 'R09', NULL, NULL, '2024-10-15 23:05:48', '2024-10-15 23:05:48'),
	(44, 'R10', NULL, NULL, '2024-10-15 23:05:49', '2024-10-15 23:05:49'),
	(45, 'R11', NULL, NULL, '2024-10-15 23:05:49', '2024-10-15 23:05:49'),
	(46, 'R12', NULL, NULL, '2024-10-15 23:05:49', '2024-10-15 23:05:49'),
	(47, 'R13', NULL, NULL, '2024-10-15 23:05:49', '2024-10-15 23:05:49'),
	(48, 'R14', NULL, NULL, '2024-10-15 23:05:50', '2024-10-15 23:05:50'),
	(49, 'R15', NULL, NULL, '2024-10-15 23:05:50', '2024-10-15 23:05:50'),
	(50, 'R16', NULL, NULL, '2024-10-15 23:05:50', '2024-10-15 23:05:50'),
	(51, 'R17', NULL, NULL, '2024-10-15 23:05:50', '2024-10-15 23:05:50'),
	(52, 'R18', NULL, NULL, '2024-10-15 23:05:50', '2024-10-15 23:05:50'),
	(53, 'R19', NULL, NULL, '2024-10-15 23:05:51', '2024-10-15 23:05:51'),
	(54, 'R21', NULL, NULL, '2024-10-15 23:05:51', '2024-10-15 23:05:51'),
	(55, 'R25', NULL, NULL, '2024-10-15 23:05:51', '2024-10-15 23:05:51'),
	(56, 'R29', NULL, NULL, '2024-10-15 23:05:51', '2024-10-15 23:05:51'),
	(57, 'R30', NULL, NULL, '2024-10-15 23:05:51', '2024-10-15 23:05:51'),
	(58, 'R35', NULL, NULL, '2024-10-15 23:05:51', '2024-10-15 23:05:51'),
	(59, 'R60', NULL, NULL, '2024-10-15 23:05:51', '2024-10-15 23:05:51'),
	(60, 'R61', NULL, NULL, '2024-10-15 23:05:51', '2024-10-15 23:05:51'),
	(61, 'R72', NULL, NULL, '2024-10-15 23:05:52', '2024-10-15 23:05:52');");
        // Ruta::factory()->create([
        //     'id' => 35,
        //     'nombre' => 'R01',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:47',
        //     'updated_at' => '2024-10-15 23:05:47',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 36,
        //     'nombre' => 'R02',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:47',
        //     'updated_at' => '2024-10-15 23:05:47',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 37,
        //     'nombre' => 'R03',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:47',
        //     'updated_at' => '2024-10-15 23:05:47',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 38,
        //     'nombre' => 'R04',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:47',
        //     'updated_at' => '2024-10-15 23:05:47',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 39,
        //     'nombre' => 'R05',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:48',
        //     'updated_at' => '2024-10-15 23:05:48',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 40,
        //     'nombre' => 'R06',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:48',
        //     'updated_at' => '2024-10-15 23:05:48',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 41,
        //     'nombre' => 'R07',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:48',
        //     'updated_at' => '2024-10-15 23:05:48',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 42,
        //     'nombre' => 'R08',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:48',
        //     'updated_at' => '2024-10-15 23:05:48',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 43,
        //     'nombre' => 'R09',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:48',
        //     'updated_at' => '2024-10-15 23:05:48',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 44,
        //     'nombre' => 'R10',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:49',
        //     'updated_at' => '2024-10-15 23:05:49',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 45,
        //     'nombre' => 'R11',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:49',
        //     'updated_at' => '2024-10-15 23:05:49',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 46,
        //     'nombre' => 'R12',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:49',
        //     'updated_at' => '2024-10-15 23:05:49',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 47,
        //     'nombre' => 'R13',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:49',
        //     'updated_at' => '2024-10-15 23:05:49',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 48,
        //     'nombre' => 'R14',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:50',
        //     'updated_at' => '2024-10-15 23:05:50',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 49,
        //     'nombre' => 'R15',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:50',
        //     'updated_at' => '2024-10-15 23:05:50',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 50,
        //     'nombre' => 'R16',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:50',
        //     'updated_at' => '2024-10-15 23:05:50',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 51,
        //     'nombre' => 'R17',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:50',
        //     'updated_at' => '2024-10-15 23:05:50',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 52,
        //     'nombre' => 'R18',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:50',
        //     'updated_at' => '2024-10-15 23:05:50',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 53,
        //     'nombre' => 'R19',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:51',
        //     'updated_at' => '2024-10-15 23:05:51',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 54,
        //     'nombre' => 'R21',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:51',
        //     'updated_at' => '2024-10-15 23:05:51',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 55,
        //     'nombre' => 'R25',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:51',
        //     'updated_at' => '2024-10-15 23:05:51',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 56,
        //     'nombre' => 'R29',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:51',
        //     'updated_at' => '2024-10-15 23:05:51',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 57,
        //     'nombre' => 'R30',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:51',
        //     'updated_at' => '2024-10-15 23:05:51',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 58,
        //     'nombre' => 'R35',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:51',
        //     'updated_at' => '2024-10-15 23:05:51',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 59,
        //     'nombre' => 'R60',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:51',
        //     'updated_at' => '2024-10-15 23:05:51',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 60,
        //     'nombre' => 'R61',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:51',
        //     'updated_at' => '2024-10-15 23:05:51',
        // ]);

        // Ruta::factory()->create([
        //     'id' => 61,
        //     'nombre' => 'R72',
        //     'color' => null,
        //     'deleted_at' => null,
        //     'created_at' => '2024-10-15 23:05:52',
        //     'updated_at' => '2024-10-15 23:05:52',
        // ]);
    }
}
