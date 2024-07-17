<?php

namespace Database\Seeders;

use App\Models\DescuentoAsociado;
use App\Models\DescuentoCatalogo;
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
        DescuentoCatalogo::factory()->count(5)->create();
        DescuentoAsociado::factory()->count(5)->create();
    }
}
