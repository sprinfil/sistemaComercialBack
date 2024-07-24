<?php

namespace Database\Seeders;

use App\Models\Lectura;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LecturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lectura::factory()->count(30)->create();
    }
}
