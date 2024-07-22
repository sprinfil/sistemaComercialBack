<?php

namespace Database\Factories;

use App\Models\AjusteCatalogo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AjusteCatalogo>
 */
class AjusteCatalogoFactory extends Factory
{
    protected $model = AjusteCatalogo::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => '',
            'descripcion' => '',
            'estado' => '',
        ];
    }
}
