<?php

namespace Database\Seeders;

use App\Models\ConceptoCatalogo;
use App\Models\TarifaServiciosDetalle;
use App\Models\TipoToma;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConceptoCatalogoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        DB::table('concepto_catalogos')->insert([
            [
                'nombre' => 'Servicio de agua potable',
                'descripcion' => 'Aqui se describe el agua potable',
                'prioridad_abono' => '1',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Servicio de alcantarillado',
                'descripcion' => 'Aqui se describe el alcantarillado',
                'prioridad_abono' => '1',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Tratamiento y saneamiento',
                'descripcion' => 'Aqui se describe el tratamiento y el sanemianto',
                'prioridad_abono' => '1',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Multas',
                'descripcion' => 'Descripcion de la multa',
                'prioridad_abono' => '2',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Costo contrato de agua potable',
                'descripcion' => 'Descripcion del costo del contrato de agua',
                'prioridad_abono' => '1',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Costo contrato de alcantarillado',
                'descripcion' => 'Descripcion del costo del contrato de alcantarillado',
                'prioridad_abono' => '1',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Reconexion de agua',
                'descripcion' => 'Descripcion del costo de la reconexion',
                'prioridad_abono' => '3',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Constancia de factibilidad de agua',
                'descripcion' => 'Descripcion del costo de la factibilidad',
                'prioridad_abono' => '4',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Constancia de no servicio',
                'descripcion' => 'Descripcion de no servicio',
                'prioridad_abono' => '4',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Constancia de servicio',
                'descripcion' => 'Descripcion de no servicio',
                'prioridad_abono' => '5',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Profundidad',
                'descripcion' => 'Descripcion de la profundidad',
                'prioridad_abono' => '5',
                'estado' => 'activo',
            ],
        ]);*/

        DB::table('tarifas')->insert([
            [
                'nombre' => 'TARIFA JULIO 2024',
                'descripcion' => 'ninguna',
                'fecha' => now(),
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $tipo_tomas = TipoToma::all();
        $tipo_tomas->each(function ($tipo_toma) {
            $rango = 17;
            for ($i = 1; $i <= 10; $i++) {
                TarifaServiciosDetalle::factory()->create([
                    'id_tarifa'=>1,
                    'id_tipo_toma'=>$tipo_toma->id,
                    'rango'=>$rango*$i,
                    'agua'=>10*17,
                    'alcantarillado'=>2*17,
                    'saneamiento'=>2*17
                ]);
            }
        });

        //ConceptoCatalogo::factory()->count(10)->create();
        $conceptos = [
            "contrato de agua" => "Contrato que regula el suministro de agua potable a una propiedad.",
            "contrato de alcantarillado y saneamiento" => "Contrato que regula el suministro de agua potable y el servicio de alcantarillado a una propiedad.",
            "factibilidad de agua" => "Estudio que determina la viabilidad de proporcionar suministro de agua a una nueva construcción.",
            "factibilidad de agua y alcantarillado" => "Estudio que determina la viabilidad de proporcionar suministro de agua y servicio de alcantarillado a una nueva construcción.",
            "derechos de conexión" => "Derechos necesarios para conectar una propiedad al sistema de suministro de agua y alcantarillado.",
            "rompimiento y levantamiento de banqueta" => "Proceso de demolición y remoción de la acera o banqueta de una vía pública. Este trabajo se realiza para llevar a cabo reparaciones subterráneas, instalación de nuevas infraestructuras o renovación de la superficie de la banqueta.",
            "rompimiento y levantamiento de pavimento asfalto" => "Proceso de demolición y remoción del pavimento de asfalto de una calle o carretera. Este trabajo se lleva a cabo para acceder a infraestructuras subterráneas, realizar reparaciones, instalar nuevas líneas de servicios públicos o renovar la capa de asfalto.",
            "rompimiento y levantamiento de pavimento hidráulico" => "Proceso de demolición y remoción del pavimento de concreto hidráulico, utilizado comúnmente en carreteras y calles de alta resistencia. Este trabajo es necesario para realizar reparaciones subterráneas, instalar nuevas infraestructuras o renovar la superficie del pavimento.",
            "tipo de suelo A (común)" => "Suelo de características comunes, generalmente compuesto por materiales como arena, arcilla y limo. Es considerado de fácil manejo para excavaciones y obras de construcción debido a su estabilidad y facilidad de compactación.",
            "tipo de suelo C (de piedra)" => "Suelo compuesto predominantemente por materiales rocosos o pedregosos. Este tipo de suelo presenta mayor dificultad para excavaciones y obras de construcción debido a su dureza y resistencia, lo que requiere el uso de maquinaria pesada y técnicas especializadas.",
            "registro a red sanitaria" => "Conexión y registro de una edificación o área a la red de alcantarillado o sistema sanitario municipal. Este proceso incluye la instalación de tuberías y accesorios necesarios para garantizar el correcto desecho de aguas residuales hacia la red de saneamiento público."  
        ];
        
        foreach ($conceptos as $nombre => $descripcion) {
            ConceptoCatalogo::factory()->create([
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'estado' => 'activo',
            ]);
        }
    }
}
