<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Http\Requests\StoreContratoRequest;
use App\Http\Requests\UpdateContratoRequest;
use App\Http\Resources\ContratoResource;
use App\Http\Resources\UsuarioResource;
use App\Models\Usuario;
use Exception;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ContratoResource::collection(
            Contrato::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Contrato $contrato,StoreContratoRequest $request)
    {
        $data=$request->validated();
        
        $contrato = Contrato::withTrashed()->where('id_usuario', $request['id_usuario'])->first();

            //VALIDACION POR SI EXISTE
            if ($contrato) {
                if ($contrato->trashed()) {
                    return response()->json([
                        'message' => 'El contrato ya existe pero ha sido eliminado. ¿Desea restaurarlo?',
                        'restore' => true,
                        'contrato_id' => $contrato->id
                    ], 200);
                }
                else{
                    return response()->json([
                        'message' => 'El contrato ya existe.',
                        'restore' => false
                    ], 200);
                }
                
            }
            else{
                $contrato = Contrato::create($data);
            return response(new ContratoResource($contrato), 201);
            }
        /*
        catch(Exception $ex){
            return response()->json([
                'error' => 'El Contrato no se pudo.',
                'restore' => false
            ], 200);
        }
       
        */
        
    }

    /**
     * Display the specified resource.
     */
    public function showPorNombre($nombres)
    {
        $usuario = Usuario::ConsultarContratoPorNombre($nombres);
        //return json_encode($usuario);
            
        return UsuarioResource::collection(
            $usuario
        );
        

        /*
        try{
            
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron contratos asociados a este usuario'], 200);
        }
            */
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
