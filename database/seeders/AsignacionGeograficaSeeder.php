<?php

namespace Database\Seeders;

use App\Models\AsignacionGeografica;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AsignacionGeograficaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AsignacionGeografica::factory()->count(30)->create();
    }
}
