<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSecuenciaRequest;
use App\Models\Libro;
use App\Models\Secuencia;
use App\Services\SecuenciaService;
use ErrorException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psy\CodeCleaner\ReturnTypePass;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNan;
use function PHPUnit\Framework\isNull;

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
            if (!$secuencia || $secuencia=="Invalido" || $secuencia=="Operador" ||$secuencia=="Padre" ||$secuencia=="No perso" ){
                $error=match($secuencia){
                    "Padre"=>"No se pudo crear secuencia padre: El libro ya tiene una secuencia padre vigente",
                    "Invalido"=>"Una secuencia padre solo se puede crear o eliminar, no se puede modificar",
                    "Operador"=>"No se puede asignar un operador a una secuencia padre",
                    "Personalizada"=>"No se pudo crear secuencia padre: El libro ya tiene una secuencia personalizada vigente para este operador",
                    "No perso"=>"No se puede cambiar el tipo de secuencia de una secuencia padre ",
                };
                return response()->json(["error"=>$error],400);
            }
            $secuencia_ordenes=(new SecuenciaService())->SecuenciaOrdenStore($secuencia,$data['secuencia_ordenes']);
            DB::commit();
            return response()->json(["secuencia"=>$secuencia,"secuencia_ordenes"=>$secuencia_ordenes],200) ;
        }
        catch(Exception | ErrorException $ex){
           
            DB::rollBack();
            $clase= get_class($ex);
            if ($clase=="ErrorException"){
                return response()->json(["error"=>"Error de petición: ".$ex->getMessage()],400);
            }
            else{
                return response()->json(["error"=>"Error de servidor: ".$ex->getMessage()],500);
            }

        }
        
    }
    public function secuenciasPadre(Request $request)
    {
        //pediente asignar permisos
        try {
            DB::beginTransaction();
            $data=$request->all();
       
            $libro=Libro::find($data['id_libro']);

            $secuencias = (new SecuenciaService())->secuencia($libro);

            return response()->json(["secuencia"=>$secuencias]);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'No se encontraron secuencia del libro: '.$ex
            ], 200);
        }
       
    }
    public function CargarSecuencia(Request $request){
       try{
        
       }
       catch(Exception $ex){

       }
    }
    public function Delete(Request $request){

    }
    
}
