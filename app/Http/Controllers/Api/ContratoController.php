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
    public function store($id_usuario, Contrato $contrato,StoreContratoRequest $request)
    {
        
        try{
        $data=$request->validated();
        $Existe=Usuario::ConsultarContratoPorUsuario($id_usuario);
        $data['folio_solicitud']=Contrato::darFolio();
        if ($Existe) {
            return response()->json([
                'message' => 'El usuario ya tiene un contrato',
                'restore' => false
            ], 200);
        }
        else{
            $contrato = Contrato::create($data);
            return response(new ContratoResource($contrato), 201);
        }

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
    public function storeCotizacion(Cotizacion $cotizacion){
        //$cotizacion = Cotizacion::create($data);
        return new CotizacionResource($cotizacion);
    }
}
