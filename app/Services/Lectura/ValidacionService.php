<?php
namespace App\Services\Lectura;

use App\Http\Resources\PeriodoResource;
use App\Models\Consumo;
use App\Models\Periodo;
use App\Models\Toma;
use Exception;

class ValidacionService{

    public function consumosperiodo ($id)
    {
        try {
            $periodo = Periodo::findOrFail($id);
            $ruta = $periodo->tieneRutas;
            $tomasConConsumos = collect();
            //si la ruta existe
            if ($ruta && $ruta->Libros && $ruta->Libros->isNotEmpty()) {
                foreach ($ruta->Libros as $libro) {
                    //si el libro tiene tomas
                    if ($libro->tomas && $libro->tomas->isNotEmpty()) {
                        foreach ($libro->tomas as $toma) {
                            //Obtenemos los consumos asociados a la toma
                            $consumos = $periodo->consumos()->where('id_toma', $toma->id)->get();
                            //Agregamos la toma con sus consumos a la colección
                            $tomasConConsumos->push([
                                'toma' => $toma,
                                'consumos' => $consumos
                            ]);
                        }
                    }
                }
            }
            //se devuelve las tomas con sus consumos
            return $tomasConConsumos->isNotEmpty() ? $tomasConConsumos : response()->json([
                'message' => 'No se encontraron tomas con consumos para este periodo'
            ], 404);
            //return response(new PeriodoResource($tomas), 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se pudo encontrar el periodo ' .$ex->getMessage()
            ], 500);
        }
    }

    public function registrarconsumo ($data, $id_toma, $id_periodo)
    {
        try {
            $periodo = Periodo::findOrFail($id_periodo);
            $toma = Toma::find($id_toma);
            if (!$toma) {
               return response()->json([
                'message' => 'No se encontró la toma. '
               ] , 404);
            }
            $consumoexistente = $periodo->consumos()->where('id_toma' , $id_toma)->first();
            if ($consumoexistente) {
                return response()->json([
                    'message' => 'La toma ya tiene un consumo en ese periodo. '
                   ] , 400);
            }
            $data = [
                'id_toma' => $id_toma,
                'id_periodo' => $id_periodo,
                'consumo'=> $data['consumo'],
            ];
            $consumo = Consumo::create($data);
            return response()->json([
                'message' => 'Se ha registrado el consumo.',
                'consumo' => $consumo
            ], 201);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al registrar el consumo. ' .$ex->getMessage()
            ], 500);
        }
    }

    public function promediar ($id_toma , $id_periodo)
    {
        try {
            $periodo = Periodo::findOrFail($id_periodo);
            $toma = Toma::find($id_toma);
            if (!$toma) {
               return response()->json([
                'message' => 'No se encontró la toma. '
               ] , 404);
            }
            $consumosanteriores = Consumo::where('id_toma' , $id_toma)
            ->whereHas('periodo' , function($query) use ($periodo){
                $query->where('validacion_inicio' , '<' , $periodo->validacion_final);
            })
            ->get();
            //Si no tiene consumos, tambien se promedia con 17 metros. 
            $valorminimo = 17;
            
            if ($consumosanteriores->isEmpty()) {
                return response()->json([
                    'message' => 'Se promedio con el consumo minimo',
                    'consumo' => $valorminimo
                   ] , 200);
            }
            $totalconsumo = 0;
            $count = 0;
            foreach ($consumosanteriores as $promedio) {
                if ($promedio->id_lectura_anterior !== null && $promedio->id_lectura_actual !== null) {
                    $lecturaAnterior = $promedio->id_lectura_anterior;
                    $lecturaActual = $promedio->id_lectura_actual;
                    //calcula el consumo de este periodo.
                    $consumopromedio = $lecturaActual - $lecturaAnterior;
                    //Se suma el total de los consumos.
                    $totalconsumo += $consumopromedio;
                    $count++;
                }
            }
            $promedioconsumo = $totalconsumo / $count;
            return response()->json([
                'message' => 'Promedio calculado',
                'promedio' => $promedioconsumo,
            ],200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al registrar el promedio. ' .$ex->getMessage()
            ], 500);
        }
    }



}