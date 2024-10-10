<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PeriodoResource;
use App\Models\Periodo;
use App\Services\Facturacion\FacturaService;
use App\Services\Facturacion\PeriodoService;
use App\Services\SecuenciaService;
use ErrorException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periodos = Periodo::orderBy('created_at', 'desc')->take(50)->get();
        return response()->json(PeriodoResource::collection($periodos));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request) //crear un request
    {
        try{
            DB::beginTransaction();
            
            $data=$request->all()['periodos'];
            $periodo=(new PeriodoService())->storePeriodo($data);
            $carga_trabajo=(new PeriodoService())->storeCargaTrabajo($periodo);
            DB::commit();
            return response()->json(["periodos"=>$periodo,"cargas_trabajo"=>$carga_trabajo],200);
        }
        catch(Exception | ErrorException $ex){
            DB::rollBack();
            $clase= get_class($ex);
            if ($clase=="ErrorException"){
                return response()->json(["error"=>"Error al crear periodos. ".$ex->getMessage()],400);
            }
            else{
                return response()->json(["error"=>"Error de servidor: ".$ex->getMessage()],500);
            }
        }

    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $periodo=(new PeriodoService())->show($id);
            return response()->json(["periodos"=>$periodo],200);
        }
        catch(Exception | ErrorException $ex){
   
            $clase= get_class($ex);
            if ($clase=="ErrorException"){
                return response()->json(["error"=>"Error de peticion. ".$ex->getMessage()],400);
            }
            else{
                return response()->json(["error"=>"Error de servidor: ".$ex->getMessage()],500);
            }
        }
        
    }
    public function showCargaTrabajo(string $id)
    {
        try{
            $periodo=(new PeriodoService())->ShowCarga($id);
            return response()->json(["cargas_trabajo"=>$periodo],200);
        }
        catch(Exception | ErrorException $ex){
   
            $clase= get_class($ex);
            if ($clase=="ErrorException"){
                return response()->json(["error"=>"Error de peticion. ".$ex->getMessage()],400);
            }
            else{
                return response()->json(["error"=>"Error de servidor: ".$ex->getMessage()],500);
            }
        }
        
    }
    public function showCargaTrabajoEncargado()
    {
        try{
            $id_encargado=helperOperadorActual();
            $periodo=(new PeriodoService())->ShowCargaEncargado($id_encargado);
            $libros= $periodo->pluck('libro');    
            $secuencias=(new SecuenciaService())->secuenciaOperador( $libros,$id_encargado);
            return $secuencias;
            return response()->json(["cargas_trabajo"=>$periodo,"secuencias"=>$secuencias],200);
        }
        catch(Exception | ErrorException $ex){
   
            $clase= get_class($ex);
            if ($clase=="ErrorException"){
                return response()->json(["error"=>"Error de peticion. ".$ex->getMessage()],400);
            }
            else{
                return response()->json(["error"=>"Error de servidor: ".$ex->getMessage()],500);
            }
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            DB::beginTransaction();
            $data=$request->all()['periodos'];
            $periodo=(new PeriodoService())->updatePeriodo($data,$id);
            DB::commit();
            return response()->json(["periodos"=>$periodo],200);
        }
        catch(Exception | ErrorException $ex){
            DB::rollBack();
            $clase= get_class($ex);
            if ($clase=="ErrorException"){
                return response()->json(["error"=>"Error de peticion. ".$ex->getMessage()],400);
            }
            else{
                return response()->json(["error"=>"Error de servidor: ".$ex->getMessage()],500);
            }
        }
    }
    public function updateCarga(Request $request){
        try{
            DB::beginTransaction();
            $data=$request->all()['cargas_trabajo'];
            $periodo=(new PeriodoService())->updateCarga($data);
            DB::commit();
            return response()->json(["cargas_trabajo"=>$periodo],200);
        }
        catch(Exception | ErrorException $ex){
            DB::rollBack();
            $clase= get_class($ex);
            if ($clase=="ErrorException"){
                return response()->json(["error"=>"Error de peticion. ".$ex /*->getMessage()*/],400);
            }
            else{
                return response()->json(["error"=>"Error de servidor: ".$ex/*->getMessage()*/],500);
            }
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
