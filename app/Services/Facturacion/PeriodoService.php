<?php

namespace App\Services\Facturacion;

use App\Models\CargaTrabajo;
use App\Models\Factura;
use App\Models\Libro;
use App\Models\Periodo;
use App\Models\Ruta;
use Carbon\Carbon;
use COM;
use Database\Seeders\LibroSeeder;
use ErrorException;
use Exception;


class PeriodoService
{

    public function storePeriodo($per)
    {

        $abierto = Periodo::whereIn('id_ruta', array_column($per, "id_ruta"))->where('estatus', 'activo')->get();
        //return $abierto->pluck('id_ruta');
        if (count($abierto) != 0) {
            $mensaje = null;
            $ruta = Ruta::whereIn('id', $abierto->pluck('id_ruta'))->get();
            foreach ($ruta as $rutaP) {
                $mensaje = $mensaje . $rutaP->nombre . " ";
            }
            throw new ErrorException("Ya existe periodo vigente para las siguentes rutas: " . $mensaje, 400);
        } else {
            //$periodo=Periodo::insert($insercion);
            $fecha = Carbon::now();
            $tarifa = (new TarifaService())->TarifaVigente()->id;
            $fecha = Carbon::parse(helperFechaAhora(), 'GMT-7');
            $insercion = array_map(function ($item) use ($tarifa, $fecha) {
                $item['estatus'] = 'activo';
                $item['nombre'] = $fecha->monthName . " " . $fecha->year; //mes y año
                $item['periodo'] = $fecha->startOfMonth()->format('Y-m-d'); //mes y año
                $item['id_tarifa'] = $tarifa;
                $item['created_at'] =   $fecha;
                $item['updated_at'] =   $fecha;
                unset($item['tipo_periodo']);
                return $item;
            }, $per);
            Periodo::insert($insercion);
            $periodo = Periodo::whereIn('id_ruta', array_column($per, "id_ruta"))->where('estatus', 'activo')->get();
            return $periodo;
        }
    }
    public function storeCargaTrabajo($periodo)
    {
        $ruta = Ruta::whereIn('id', $periodo->pluck('id_ruta'))->get();
        $librosID = $ruta->pluck('Libros');
        $arreglo = $librosID->flatten()->all();
        //return $arreglo;
        $id = array_column($arreglo, "id");
        //return $id;
        $fecha = Carbon::now();
        $carga_Existente = CargaTrabajo::whereIn('id_libro', $id)->whereNot('estado', 'concluida')->whereNot('estado', 'cancelada')->get();
        //return $carga_Existente;

        if (count($carga_Existente) != 0) {
            $librosExistentes = Libro::with('tieneRuta:id,nombre')->whereIn('id', $carga_Existente->pluck('id_libro'))->get();
            $mensaje = null;
            foreach ($librosExistentes as $existe) {
                $rutaLibro = $existe->tieneRuta->nombre ?? null;
                if ($rutaLibro) {
                    if (!$mensaje) {
                        $mensaje = $rutaLibro . " " . $existe->nombre;
                    } else {
                        $mensaje = $mensaje . ", " . $rutaLibro . " " . $existe->nombre;
                    }
                }
            }
            throw new ErrorException("Ya existe una carga de trabajo vigente para los siguentes libros: " . $mensaje, 400);
        }
        $carga_trabajo = [];
        foreach ($ruta as $r) {
            $libros = $r['libros'];
            foreach ($libros as $l) {
                $perLibro = $periodo->firstWhere('id_ruta', $l['id_ruta']); //Consulta más rápida en collección pre cargada

                $carga['id_libro'] = $l['id'];
                $carga['id_periodo'] = $perLibro['id'];
                $carga['estado'] = "no asignada";
                $carga['created_at'] =   $fecha;
                $carga['updated_at'] =   $fecha;
                //$carga['id_operador']=helperOperadorActual();
                $carga_trabajo[] = $carga;
            }
        }
        CargaTrabajo::insert($carga_trabajo);
        $cargados = CargaTrabajo::whereIn('id_periodo', $periodo->pluck('id'))->where('estado', 'no asignada')->get(); //valido que el libro tiene una carga activa?
        return $cargados;
    }
    public function show($id)
    {
        $periodo = Periodo::where('id_ruta', $id)->orderby("id", "desc")->get();
        return $periodo;
    }
    public function updatePeriodo($per, $id)
    {
        $periodo = Periodo::find($id);
        $periodo->update($per);
        $periodo->save();
        return $periodo;
    }
}
