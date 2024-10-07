<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PeriodoResource;
use App\Models\Periodo;
use App\Services\Facturacion\FacturaService;
use App\Services\Facturacion\PeriodoService;
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
        $periodos = Periodo::orderBy('created_at', 'desc')->get();
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
            DB::rollBack();
            return response()->json(["periodos"=>$periodo],200);
        }
        catch(Exception | ErrorException $ex){
            DB::rollBack();
            $clase= get_class($ex);
            if ($clase=="ErrorException"){
                return response()->json(["error"=>"Error al crear periodos: ".$ex->getMessage()],400);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
