<?php

namespace Database\Seeders;

use App\Models\CajaCatalogo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CajaCatalogoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CajaCatalogo::factory()->count(100)->create();
    }
}
