<?php

namespace Database\Factories;

use App\Models\ConceptoAplicable;
use App\Models\TipoTomaAplicable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConceptoAplicable>
 */
class TipoTomaAplicableFactory extends Factory
{
    protected $model = TipoTomaAplicable::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_modelo' => 0,
            'modelo_origen' => '',
            'id_tipo_toma' => ''
        ];
    }
}
