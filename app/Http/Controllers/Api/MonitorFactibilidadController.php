<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MonitorFactibilidad;
use App\Http\Requests\StoreMonitorFactibilidadRequest;
use App\Http\Requests\UpdateMonitorFactibilidadRequest;
use App\Http\Resources\CargoResource;
use App\Http\Resources\FactibilidadResource;
use App\Models\Cargo;
use App\Models\Factibilidad;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class MonitorFactibilidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            //Muestra todas las factibilidades de los contratos
           return $factibilidades = Factibilidad::with('contrato')->paginate(10);
             $data = [];
                    return response()->json([
                     $data[] = [
                         'data'=>$factibilidades,
                     ]
                    ]);
        }
        catch(Exception $ex){
            return response()->json([
                'error' => 'No se encontro ningun contrato'.$ex ,
                'restore' => false
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMonitorFactibilidadRequest $request)
    {
        //
    }

    public function filtro (Request $request)
    {
        try{
            $contrato = $request->input('id_contrato');
            $agua_factible = $request->input('agua_estado_factible');
            $alcantarillado_factible = $request->input('alc_estado_factible');
            $derechos_conexion = $request->input('derechos_conexion');
            //$tipo_toma = $request->input('tipo_toma');
            $query = Factibilidad::query();
                if ($contrato) {
                    $query->where('id_contrato' , $contrato);
                }
                if ($agua_factible) {
                    $query->where('agua_estado_factible' , $agua_factible);
                }
                if ($alcantarillado_factible) {
                    $query->where('alc_estado_factible' , $alcantarillado_factible);
                }
                if ($derechos_conexion) {
                    $query->where('derechos_conexion', $derechos_conexion);
                }
                $hasConditions = !empty($query->getQuery()->wheres);
                if (!$hasConditions) {
                   return response()->json([
                    'message'=>'No se encontraron resultados',
                   ]);
                }
            //Muestra las factibilidades filtradas
            return $factibilidades = $query->with('contrato')->paginate(10);
        }
        catch(Exception $ex){
            return response()->json([
                'error' => 'No se encontro ningun contrato'.$ex ,
                'restore' => false
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        //Pendiente el filtrar ------neh------
        try {
            $request->validate();
            return $request;
            $contrato = $request->input('id_contrato');
            $agua_estado_factible = $request->input('agua_estado_factible');
            $alc_estado_factible = $request->input('alc_estado_factible');
            $query = Factibilidad::query();
                if ($contrato) {
                    $query->where('id_contrato' , $contrato);
                }
            
            return $factibilidades = $query->with('contrato')->paginate(10);
            //return $fact = Factibilidad::find($id);
            //$factibilidades = Factibilidad::find($id)->with('contrato')->paginate(10);
            $data = [];
            foreach ($factibilidades as $fact) {
                return response()->json([
                 //'data'=>$factibilidad,
                 $data[] = [
                     'data'=>$fact,
                 ]
                ]);  
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el cargo'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMonitorFactibilidadRequest $request, MonitorFactibilidad $monitorFactibilidad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MonitorFactibilidad $monitorFactibilidad)
    {
        //
    }
}
