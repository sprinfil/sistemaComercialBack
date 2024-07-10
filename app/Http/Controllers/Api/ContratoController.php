<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Http\Requests\StoreContratoRequest;
use App\Http\Requests\UpdateContratoRequest;
use App\Http\Resources\ContratoResource;
use App\Http\Resources\UsuarioResource;
use App\Models\Usuario;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return ContratoResource::collection(
                Contrato::all()
            );
        }
        catch(Exception $ex){
            return response()->json([
                'error' => 'No hay contratos.',
                'restore' => false
            ], 200);
        }
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Contrato $contrato,StoreContratoRequest $request)
    {
        try{
        $data=$request->validated();
        $folio = Contrato::withTrashed()->max('folio_solicitud');

        
        if ($folio){
            $num=substr($folio,0,5)+1;
            switch(strlen(strval($num))){
                case 1:
                    $num="0000".$num;
                     break;
                case 2:
                    $num="000".$num;
                    break;
                case 3:
                    $num="00".$num;
                    break;
                case 4:
                    $num="0".$num;
                    break;
            }
            $folio=$num.substr($folio,5,5);
        }
        else{
            $folio="00001/".Carbon::now()->format('Y');
         
        }
        $data['folio_solicitud']=$folio;
        //$contrato = Contrato::create($data);
        //return response(new ContratoResource($contrato), 201);
        return $data;
        }
        catch(Exception $ex){
            return response()->json([
                'error' => 'El Contrato no se pudo crear.',
                'restore' => false
            ], 200);
        }
       
        
        
    }

    /**
     * Display the specified resource.
     */
    public function showPorNombre($nombres)
    {
        try{
            $usuario = Usuario::ConsultarContratoPorNombre($nombres);
        //return json_encode($usuario);
            
        return UsuarioResource::collection(
            $usuario
        );
        
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron contratos asociados a este usuario'], 200);
        }
            
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContratoRequest $request, Contrato $contrato)
    {
       
        
        try{
        $data=$request->validated();
        $contrato=Contrato::find($request->id);
        $contrato->update($data);
        $contrato->save();
        return new ContratoResource($contrato);
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se pudo modificar el contrato, introduzca datos correctos'], 200);
        }
            
    }
    public function destroy(Contrato $contrato, Request $request)
    {
        try
        {
            $contrato = Contrato::findOrFail($request["id"]);
            $contrato->delete();
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        }
        catch (\Exception $e) {

            return response()->json(['message' => 'error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function restaurarDato(Contrato $contrato, Request $request)
    {
        $contrato = Contrato::withTrashed()->findOrFail($request->id);

        // Verifica si el registro está eliminado
     if ($contrato->trashed()) {
         // Restaura el registro
         $contrato->restore();
         return response()->json(['message' => 'El contrato ha sido restaurado.'], 200);
     }
    }
}
