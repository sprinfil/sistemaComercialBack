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

                            $toma->nombre_ruta = $ruta->nombre;
                            $toma->nombre_libro = $libro->nombre;
                            //Obtenemos los consumos asociados a la toma
                            $consumos = $periodo->consumos()->where('id_toma', $toma->id)->get();
                            $anomalias = [];
                            foreach ($consumos as $consumo) {
                                $lecturaactual = $consumo->lecturaActual;
                                if ($lecturaactual && $lecturaactual->anomalia) {
                                    $anomalias[] = $lecturaactual->anomalia->nombre;
                                }
                            }
                            //Agregamos la toma con sus consumos a la colección
                            $tomasConConsumos->push([
                                'toma' => $toma,
                                'consumos' => $consumos,
                                //'anomalias' => $anomalias
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
       // Buscar el periodo y la toma
       $periodoActual = Periodo::findOrFail($id_periodo);
       $toma = Toma::find($id_toma);

       if (!$toma) {
           return response()->json(['message' => 'No se encontró la toma.'], 404);
       } 
       /* 
       $consumoExistente = Consumo::where('id_toma', $id_toma)
       ->where('id_periodo', $id_periodo)
       ->first();
       if ($consumoExistente) {
        return response()->json([
            'message' => 'Ya existe un consumo registrado para esta toma en este periodo.'
        ], 400);
       } 
        */
       //Obtener consumos anteriores de la toma en periodos anteriores al actual
       $consumosAnteriores = Consumo::where('id_toma', $id_toma)
           ->whereHas('periodo', function($query) use ($periodoActual) {
               $query->where('validacion_inicio', '<', $periodoActual->validacion_final);
           })
           ->pluck('consumo');  // Obtener sólo los valores de consumo

       $valorMinimo = 17;

       $promedioConsumo = $consumosAnteriores->isEmpty() 
           ? $valorMinimo  //Si no tiene consumos, se usa el valor minimo
           : $consumosAnteriores->avg(); //calcula el promedio

       $nuevoConsumo = Consumo::create([
           'id_toma' => $id_toma,
           'id_periodo' => $id_periodo,
           'consumo' => $promedioConsumo
       ]);

       return response()->json([
           'message' => 'Consumo registrado exitosamente.',
           'promedio' => $promedioConsumo,
           'consumo_registrado' => $nuevoConsumo
       ], 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al registrar el promedio. ' .$ex->getMessage()
            ], 500);
        }
    }



}