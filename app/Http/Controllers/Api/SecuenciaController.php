<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSecuenciaRequest;
use App\Models\Secuencia;
use App\Services\SecuenciaService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\FuncCall;

class SecuenciaController extends Controller
{
    public function index(){
        return Secuencia::with('ordenesSecuencia')->take(100)->get();
    }
    public function store(StoreSecuenciaRequest $request){
      
        try{
            DB::beginTransaction();
            $data=$request->validated();
            $secuencia=(new SecuenciaService())->Store($data['secuencia']);

            if (!$secuencia || $secuencia=="Invalido"){
                $error=match($secuencia){
                    null=>"No se pudo crear secuencia padre: El libro ya tiene una secuencia padre vigente",
                    "Invalido"=>"Una secuencia padre solo se puede crear o eliminar, no se puede modificar"
                };
                return response()->json(["error"=>$error],400);
            }
            $secuencia_ordenes=(new SecuenciaService())->SecuenciaOrdenStore($secuencia,$data['secuencia_ordenes']);
            DB::commit();
            return response()->json(["secuencia"=>$secuencia,"secuencia_ordenes"=>$secuencia_ordenes],200) ;
        }
        catch(Exception $ex){
            DB::rollBack();
            return response()->json(["error"=>"Ha habido un error: ".$ex],500);
        }
    }
    
}
