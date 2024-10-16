<?php
namespace App\Services\Lectura;

use App\Http\Resources\PeriodoResource;
use App\Models\Periodo;
use Exception;

class ValidacionService{

    public function anomaliasperiodo ($id)
    {
        try {
            $anomalias = Periodo::findOrFail($id);
            $tomas = $anomalias->tieneRutas;
            return $tomas;
            //return response(new PeriodoResource($anomalias), 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se pudo encontrar el periodo' .$ex->getMessage()
            ], 500);
        }
    }



}