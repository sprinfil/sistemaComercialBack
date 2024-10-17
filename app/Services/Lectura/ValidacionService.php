<?php
namespace App\Services\Lectura;

use App\Http\Resources\PeriodoResource;
use App\Models\Periodo;
use Exception;

class ValidacionService{

    public function anomaliasperiodo ($id)
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



}