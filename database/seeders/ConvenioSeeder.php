<?php

namespace Database\Seeders;

use App\Models\ConvenioCatalogo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConvenioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $convenios = [
            "Convenio de Facturación de Verano" => "20% de convenio en el servicio de agua debido a un error en la facturación durante la temporada de verano y 100% de ajuste en el recargo.",
            "Convenio de Facturación de Invierno" => "15% de convenio en el servicio de alcantarillado debido a un error en la facturación durante la temporada de invierno y 100% de ajuste en el recargo.",
            "Convenio de Facturación por Lealtad" => "5% de convenio adicional en el servicio de agua para clientes recurrentes debido a un error en la facturación.",
            "Convenio de Facturación de Cumpleaños" => "25% de convenio en una factura de servicio de alcantarillado durante el mes de tu cumpleaños y 100% de ajuste en el recargo debido a un error en la aplicación del descuento de cumpleaños.",
            "Convenio de Facturación Estudiantil" => "30% de convenio en el servicio de agua para estudiantes con identificación válida debido a un descuento no aplicado.",
            "Convenio de Facturación Militar" => "20% de convenio en el servicio de saneamiento para personal militar activo y veteranos, y 100% de ajuste en el recargo debido a un error en la facturación.",
            "Convenio de Facturación para Mayores" => "15% de convenio en servicios de agua y alcantarillado para personas mayores de 65 años debido a un error en la facturación.",
            "Convenio por Referencia" => "10% de convenio en el servicio de agua cuando refieres a un amigo que se suscribe debido a un descuento no aplicado.",
            "Convenio de Facturación por Volumen" => "convenio variable en el servicio de agua basado en el volumen de consumo debido a un error en la facturación.",
            "Convenio de Facturación de Fin de Semana" => "15% de convenio en el servicio de alcantarillado durante los fines de semana debido a un error en la facturación.",
            "Convenio de Liquidación" => "50% de convenio en tarifas de servicios de saneamiento seleccionadas en liquidación debido a un error en la facturación.",
            "Convenio de Facturación de Membresía" => "20% de convenio en el servicio de agua para miembros con suscripción activa debido a un error en la facturación.",
            "Convenio de Facturación por Pago en Efectivo" => "5% de convenio en servicios de agua pagados en efectivo debido a un error en la facturación.",
            "Convenio de Temporada" => "Convenio variable en servicios de alcantarillado y saneamiento basado en la temporada del año y 100% de ajuste en el recargo debido a un error en la facturación."
        ];
        
        foreach ($convenios as $nombre => $descripcion) {
            ConvenioCatalogo::factory()->create([
                'nombre' => ucfirst(strtolower($nombre)),
                'descripcion' => ucfirst(strtolower($descripcion)),
                'estado' => 'activo',
            ]);
        }
        
    }
}
