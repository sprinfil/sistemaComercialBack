<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Http\Requests\StoreContratoRequest;
use App\Http\Requests\UpdateContratoRequest;
use App\Http\Resources\ContratoResource;
use App\Http\Resources\CotizacionResource;
use App\Http\Resources\UsuarioResource;
use App\Models\Cotizacion;
use App\Models\Usuario;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
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
        $data=$request->validated();
        $id_usuario=$request->input('id_usuario');
        $servicio=$request->input('servicio_contratados');
        $contratos=Contrato::contratoRepetido($id_usuario, $servicio)->get();
   
      
        //$data['folio_solicitud']=Contrato::darFolio();
        if (count($contratos)!=0) {
            return response()->json([
                'message' => 'El usuario ya tiene un contrato',
                'restore' => false
            ], 200);
        }
        else{
            $c=new Collection();
            foreach ($servicio as $sev){
                $CrearContrato=$data;
                $CrearContrato['folio_solicitud']=Contrato::darFolio();
                $CrearContrato['servicio_contratado']=$sev;
                $c->push(Contrato::create($CrearContrato));
            }
            
            return response(ContratoResource::collection($c), 201);
            //return $c;
        }
            
        /*
        try{
        

        }
        catch(Exception $ex){
            return response()->json([
                'error' => 'El Contrato no se pudo crear.',
                'restore' => false
            ], 200);
        }
            */
       
        
        
    }

    /**
     * Display the specified resource.
     */
    public function showPorUsuario($nombres)
    {
        try{
            $usuario = Usuario::ConsultarContratoPorUsuario($nombres);
        //return json_encode($usuario);
            
        return UsuarioResource::collection(
            $usuario
        );
        
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron contratos asociados a este usuario'], 200);
        }
            
    }
    #TODO
    public function showPorFolio($folio) ///falta moverle
    {
        try{
            $usuario = Contrato::ConsultarPorFolio($folio);
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
    //// COTIZACION
    public function indexCotizacion()
    {
        try{
            return Cotizacion::collection(
                Cotizacion::all()
            );
        }
        catch(Exception $ex){
            return response()->json([
                'error' => 'No hay cotizaciones.',
                'restore' => false
            ], 200);
        }
       
    }
    public function crearCotizacion($id_usuario,Cotizacion $cotizacion){
       $data=Usuario::ConsultarCotizacionPorUsuario($id_usuario);
       return $data;
        //return new CotizacionResource($cotizacion);
    }
}
