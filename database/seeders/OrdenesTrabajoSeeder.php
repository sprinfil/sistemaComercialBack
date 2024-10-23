<?php

namespace Database\Seeders;

use App\Models\OrdenesTrabajoCargo;
use App\Models\OrdenesTrabajoEncadenada;
use App\Models\OrdenTrabajo;
use App\Models\OrdenTrabajoAccion;
use App\Models\OrdenTrabajoCatalogo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdenesTrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ordenes = [
            // ["nombre" => "Reparación de fugas en toma dom", "descripcion" => "Reparación de fugas en toma domiciliaria", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Reparación de fugas en líneas", "descripcion" => "Reparación de fugas en líneas de conducción", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Reparación de fugas en caja", "descripcion" => "Reparación de fugas en caja de válvulas", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Instalación y/o cambio de válv", "descripcion" => "Instalación y/o cambio de válvulas", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Obra civil", "descripcion" => "Obra civil", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Instalación de medidores", "descripcion" => "Instalación de medidores", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Ingreso de a fosas, pozos de visita", "descripcion" => "Ingreso de a fosas, pozos de visita, y drenajes para desasolve", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Manejo de Vehículo Aquatech", "descripcion" => "Manejo de vehículo aquatech", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Manejo de VehÍculo Cisterna Pipa", "descripcion" => "Manejo de vehÍculo cisterna pipa", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Recepción y Distribución de Hipoclorito", "descripcion" => "Recepción y Distribución de hipoclorito al 13% en pozos", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Instalación de tomas domicilia", "descripcion" => "Instalación de tomas domiciliarias de agua potable", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Instalación de líneas de condu", "descripcion" => "Instalación de líneas de conducción de agua potable", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Instalación de descarga domici", "descripcion" => "Instalación de descarga domiciliaria", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Instalación de línea de drenaj", "descripcion" => "Instalación de línea de drenaje sanitario", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Supensión del servicio de agua", "descripcion" => "Supensión del servicio de agua en llave de paso", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Supensión del servicio de agua", "descripcion" => "Supensión del servicio de agua por excavación (pavimento o tierra)", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Suspensión de drenaje domicili", "descripcion" => "Suspensión de drenaje domiciliario", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Suspensión de drenaje en pozo", "descripcion" => "Suspensión de drenaje en pozo de visita", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Reparación de fugas en toma do", "descripcion" => "Reparación de fugas en toma domiciliaría", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Reparación de fugas en líneas", "descripcion" => "Reparación de fugas en líneas de conducción", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Instalación y/o cambio de válv", "descripcion" => "Instalación y/o cambio de válvulas", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Carga y Descarga de material", "descripcion" => "Carga y Descarga de material de construcción", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Manejo de VehÍculo Cisterna Pi", "descripcion" => "Manejo de vehÍculo cisterna pipa", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Manejo de Vehículo Aquatech", "descripcion" => "Manejo de vehículo Aquatech", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Recepción y Distribución de Hi", "descripcion" => "Recepción y Distribución de hipoclorito al 13% en pozos", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Instalación de medidores", "descripcion" => "Instalación de medidores", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Inspecciones oculares", "descripcion" => "Inspecciones oculares", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Supervisión de obra", "descripcion" => "Supervisión de obra", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Inspección ocular en campo", "descripcion" => "Inspección ocular en campo", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Recorrido en campo", "descripcion" => "Recorrido en campo", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Limitación", "descripcion" => "Limitación", "vigencias" => 24.00, "genera_masiva" => 1],
            // ["nombre" => "Insp consumo elevado", "descripcion" => "Inspecciones por consumo elevado", "vigencias" => 24.00, "genera_masiva" => 1],
            // ["nombre" => "Reconexión", "descripcion" => "Reconexión", "vigencias" => 24.00, "genera_masiva" => 0],
            // ["nombre" => "Instalación de toma", "descripcion" => "Instalación de toma de agua", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Instalación drenaje", "descripcion" => "Instalación de drenaje", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Saneamiento", "descripcion" => "Instalación de saneamiento", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Inspección de consumos", "descripcion" => "Monitoreo de altos y bajos consumos", "vigencias" => 72.00, "genera_masiva" => 0],
            
            ["nombre" => "Limitación", "servicio" => "CONSUMO DE AGUA POTABLE", "descripcion" => "Limitación", "vigencias" => 24.00, "momento_cargo" => "generar", "genera_masiva" => 1, "asigna_masiva" => 1, "cancela_masiva" => 1, "cierra_masiva" => 0, "publico_general" => 1, "limite_ordenes" => 1],
    
            ["nombre" => "Insp consumo elevado", "servicio" => "OTRO", "descripcion" => "Inspecciones por consumo elevado", "vigencias" => 24.00, "momento_cargo" => "No genera", "genera_masiva" => 1, "asigna_masiva" => 1, "cancela_masiva" => 1, "cierra_masiva" => 0, "publico_general" => 1, "limite_ordenes" => 2],
            
            ["nombre" => "Reconexión", "servicio" => "CONSUMO DE AGUA POTABLE", "descripcion" => "Reconexión", "vigencias" => 24.00, "momento_cargo" => "No genera", "genera_masiva" => 1, "asigna_masiva" => 1, "cancela_masiva" => 1, "cierra_masiva" => 0, "publico_general" => 1, "limite_ordenes" => 1],

            ["nombre" => "Instalación agua", "servicio" => "CONSUMO DE AGUA POTABLE", "descripcion" => "servicio de agua", "vigencias" => 24.00, "momento_cargo" => "No genera", "genera_masiva" => 1, "asigna_masiva" => 1, "cancela_masiva" => 1, "cierra_masiva" => 0, "publico_general" => 1, "limite_ordenes" => 1]

            // ["nombre" => "Suspensión de servicios", "descripcion" => "Suspensión de servicios del contrato", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Inactivar contrato", "descripcion" => "Inactivar contrato para cancelación", "vigencias" => 96.00, "genera_masiva" => 0],
            // ["nombre" => "Activación toma", "descripcion" => "Activación de los servicios de agua", "vigencias" => 96.00, "genera_masiva" => 0],
            // ["nombre" => "Activación toma", "descripcion" => "Activación de los servicios de drenaje", "vigencias" => 96.00, "genera_masiva" => 0],
            // ["nombre" => "Activación toma", "descripcion" => "Activación de los servicio de trata. y saneamiento", "vigencias" => 96.00, "genera_masiva" => 0],
            // ["nombre" => "Mantenimiento a medidores", "descripcion" => "Mantenimiento a medidores", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Cambio de giro", "descripcion" => "Inspección cambio de giros", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Fact Inst Serv Agua", "descripcion" => "Factibilidad de instalación del servicio de agua", "vigencias" => 48.00, "genera_masiva" => 0],
            // ["nombre" => "Fact Inst Serv Dren", "descripcion" => "Factibilidad de instalación del servicio de drenaje", "vigencias" => 48.00, "genera_masiva" => 0],
            // ["nombre" => "Inact Serv Saneamiento", "descripcion" => "Inactivar servicio de saneamiento", "vigencias" => 24.00, "genera_masiva" => 0],
            // ["nombre" => "Inact Serv Alcantarillado", "descripcion" => "Inactivación del servicio de alcantarillado", "vigencias" => 24.00, "genera_masiva" => 0],
            // ["nombre" => "Multa", "descripcion" => "Multa", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Manipulación de toma", "descripcion" => "Manipulación de toma", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Corte de drenaje", "descripcion" => "Corte de drenaje", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Reconexión drenaje", "descripcion" => "Reconexión de drenaje", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Cambio medidor", "descripcion" => "Cambio medidor migración", "vigencias" => 24.00, "genera_masiva" => 0],
            // ["nombre" => "Cancelar contrato", "descripcion" => "Cancelación de contrato", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Reconexión drenaje", "descripcion" => "Reconexión drenaje", "vigencias" => 24.00, "genera_masiva" => 0],
            // ["nombre" => "Cambio medidor", "descripcion" => "Cambio medidor", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Quitar medidor", "descripcion" => "Eliminar numero de medidor", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Inspección Padrón", "descripcion" => "Verificar Padrón", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Recuperacion de medidor", "descripcion" => "Recuperacion de medidor", "vigencias" => 72.00, "genera_masiva" => 0],
            // ["nombre" => "Activar contratos de baja", "descripcion" => "Contratos de baja", "vigencias" => 24.00, "genera_masiva" => 0],
        ];
        $ordenesAccionInstalacion=[
        ["id_orden_traabjo_catalogo"=>4,"accion"=>"modificar","modelo"=>"toma","campo"=>"contrato_agua","valor"=>"activa"],
        ["id_orden_traabjo_catalogo"=>4,"accion"=>"modificar","modelo"=>"toma","campo"=>"contrato_alcantarillado","valor"=>"activa"],
        ["id_orden_traabjo_catalogo"=>4,"accion"=>"modificar","modelo"=>"toma","campo"=>"contrato_saneamiento","valor"=>"activa"]
        ];
        foreach ($ordenes as $orden) {
            OrdenTrabajoCatalogo::factory()->create([
                'nombre' => ucfirst(strtolower($orden['nombre'])),
                'servicio' => ucfirst(strtolower($orden['servicio'])),
                'descripcion' => ucfirst(strtolower($orden['descripcion'])),
                'vigencias' => $orden['vigencias'],  // Si es un número, no hace falta convertirlo a mayúsculas
                'momento_cargo' => ucfirst(strtolower($orden['momento_cargo'])),
                'genera_masiva' => $orden['genera_masiva'], // Booleano, no hace falta modificar
                'asigna_masiva' => $orden['asigna_masiva'], // Booleano, no hace falta modificar
                'cancela_masiva' => $orden['cancela_masiva'], // Booleano, no hace falta modificar
                'cierra_masiva' => $orden['cierra_masiva'], // Booleano, no hace falta modificar
                'publico_general' => $orden['publico_general'], // Booleano, no hace falta modificar
                'limite_ordenes' => $orden['limite_ordenes']  // Si es un número, no hace falta modificar
            ]);
        }
        OrdenTrabajoAccion::insert($ordenesAccionInstalacion);

        /*OrdenesTrabajoCargo::factory()->count(10)->create();
        OrdenesTrabajoEncadenada::factory()->count(10)->create();
        OrdenTrabajoAccion::factory()->count(10)->create();*/
        OrdenTrabajo::factory()->count(1000)->create();
    }
}
