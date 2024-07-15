<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Http\Requests\StoreContratoRequest;
use App\Http\Requests\StoreCotizacionRequest;
use App\Http\Requests\UpdateContratoRequest;
use App\Http\Requests\UpdateCotizacionRequest;
use App\Http\Resources\ContratoResource;
use App\Http\Resources\CotizacionResource;
use App\Http\Resources\UsuarioResource;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
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
        $id_toma=$request->input('id_toma');
        $servicio=$request->input('servicio_contratados');
        $contratos=Contrato::contratoRepetido($id_usuario, $servicio,$id_toma)->get();
   
      
        //$data['folio_solicitud']=Contrato::darFolio();
        if (count($contratos)!=0) {
            
            return response()->json([
                'message' => 'El usuario y/o toma ya tiene un contrato',
                'restore' => false
            ], 200);
            
            //return $contratos;
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
            
       
        
        
    }

    /**
     * Display the specified resource.
     */
    public function showPorUsuario($id)
    {
        try{
            $usuario=Usuario::find($id);
            $contratos = $usuario->contratovigente;
        //return json_encode($usuario);
            
        return ContratoResource::collection(
            $contratos
        );
        
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron contratos asociados a este usuario'], 200);
        }
            
    }
    
    public function showPorFolio($folio,$ano) ///falta moverle
    {
        try{
            $usuario = Contrato::ConsultarPorFolio($folio,$ano);
        return ContratoResource::collection(
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
    public function showCotizacion($id_contrato) 
    {
        try{
            $cotizacion=Contrato::find($id_contrato)->first();
        return ContratoResource::collection(
            $cotizacion
        );
        
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron cotizaciones asociadas a este contrato'], 200);
        }
            
    }
    public function crearCotizacion(Cotizacion $cotizacion, StoreCotizacionRequest $request){
       
        try{
            $data=$request->validated();
            $id_contrato=$request['id_contrato'];
            $cotizacion=Contrato::find($id_contrato)->cotizacionesVigentes->all();
            if ($cotizacion){
                return response()->json(['message' => 'El contrato ya tiene una cotización vigente'], 200);
            }
        else{

            return new CotizacionResource(Cotizacion::create($data));
        }
      
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se pudo crear la cotización, introduzca datos correctos'], 200);
        }
    }
    public function terminarCotizacion(Cotizacion $cotizacion, UpdateCotizacionRequest $request){
        
       
         try{
            $data=$request->validated();
            $cotizacion=Cotizacion::find($data['id_cotizacion']);
         if ($cotizacion){
            $cotizacion->update($data);
            $cotizacion->save();
            return new CotizacionResource($cotizacion);
         }
         else{
            //return $data;
            return response()->json(['message' => 'El contrato no tiene una cotización vigente'], 200);
         }
         }
         catch(Exception $ex){
            return response()->json(['message' => 'La cotización no se puede cerrar'], 200);
         }
         
     }
     public function destroyCot(Cotizacion $Cotizacion, Request $request)
    {
        try
        {
            $Cotizacion = Cotizacion::findOrFail($request["id"]);
            $Cotizacion->delete();
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        }
        catch (\Exception $e) {

            return response()->json(['message' => 'error'], 500);
        }
    }
    public function restaurarCot(Cotizacion $cotizacion, Request $request)
    {
        $cotizacion = Cotizacion::withTrashed()->findOrFail($request->id);

        // Verifica si el registro está eliminado
     if ($cotizacion->trashed()) {
         // Restaura el registro
         $cotizacion->restore();
         return response()->json(['message' => 'El contrato ha sido restaurado.'], 200);
     }
    }

    /////COTIZACION DETALLE
    public function indexCot()
    {
        try{
            return CotizacionDetalle::collection(
                CotizacionDetalle::all()
            );
        }
        catch(Exception $ex){
            return response()->json([
                'error' => 'No hay comceptos de cotizacion.',
                'restore' => false
            ], 200);
        }
       
    } 
}
