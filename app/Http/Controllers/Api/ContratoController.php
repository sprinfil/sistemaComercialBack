<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArchivoRequest;
use App\Models\Contrato;
use App\Http\Requests\StoreContratoRequest;
use App\Http\Requests\StoreCotizacionDetalleRequest;
use App\Http\Requests\StoreCotizacionRequest;
use App\Http\Requests\UpdateContratoRequest;
use App\Http\Requests\UpdateCotizacionRequest;
use App\Http\Resources\ArchivoResource;
use App\Http\Resources\CargoResource;
use App\Http\Resources\ContratoResource;
use App\Http\Resources\CotizacionDetalleResource;
use App\Http\Resources\CotizacionResource;
use App\Http\Resources\TomaResource;
use App\Http\Resources\UsuarioResource;
use App\Models\Cargo;
use App\Models\ConceptoCatalogo;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Tarifa;
use App\Models\TarifaConceptoDetalle;
use App\Models\Toma;
use App\Models\Usuario;
use App\Services\ArchivoService;
use App\Services\Caja\CargoService;
use App\Services\ContratoService;
use App\Services\CotizacionService;
use App\Services\OrdenTrabajoService;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use ErrorException;

use function PHPUnit\Framework\isEmpty;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(["contrato" => ContratoResource::collection(
            Contrato::with('usuario', 'toma.tipoToma','toma','calle1','entre_calle_1','entre_calle_2','colonia1')->orderBy('created_at', 'desc')->get()
        )]);
        try {
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No hay contratos.',
                'restore' => false
            ], 200);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Contrato $contrato, StoreContratoRequest $request)
    {
           
        try {
             ////Cambiar estatus y poner id de contrato en servicios de toma
       DB::beginTransaction();
       $datos = $request->validated();
       $data = $datos['contrato'];
       $solicitud = $datos['solicitud_factibilidad'] ?? false;
       $nuevaToma = $request->validated()['toma'] ?? null;
       $id_usuario = $request['contrato']['id_usuario'];
       $id_toma = $request['contrato']['id_toma'] ?? null;
       $servicio = $request['contrato']['servicio_contratados'];
       $OT = $request['ordenes_trabajo'][0] ?? null;
       $contratos = Contrato::contratoRepetido($id_usuario, $servicio, $id_toma)->get();
       // TO DO
       if (count($contratos) != 0) {

           return response()->json([
               'message' => 'La toma ya tiene un contrato',
               'restore' => false
           ], 500);

           //return $contratos;
       } else {

           $EsPreContrato = Toma::find($id_toma)['tipo_contratacion'] ?? null;
           $toma = (new ContratoService())->SolicitudToma($nuevaToma, $id_usuario, $data);
           if (isset($toma['message'])){
               return response()->json([
                   'message' => $toma['message']
               ], 500);
           }

           $c = (new ContratoService())->Solicitud($servicio, $data, $toma, $solicitud, $EsPreContrato);
           $cargos=null;
        
           ///opcional
           $CargoPreContrato=false;
           if ($toma['tipo_contratacion']=="pre-contrato" && $CargoPreContrato){
               $concepto=ConceptoCatalogo::where('id',32)->get(); ///cambio de propietario
               $cargos=(new OrdenTrabajoService())->generarCargo($toma,'toma',$toma,'toma',$concepto);
           }
           $toma->giroComercial;
           $toma=$toma->fresh();
           DB::commit();
           return response()->json(["contrato" => $c,/*"Orden_trabajo"=>$ordenTrabajo,*/ "toma" => $toma, "cargo"=>$cargos], 201);

       }   
        } 
        catch (Exception $ex) {
            DB::rollBack();
            return response()->json(["Error" => "No se pudo crear solicitud de contrato: ".$ex], 500);
        }
    }
    public function storeFile(StoreArchivoRequest $request)
    {
        try {
            return response()->json(new ArchivoResource((new ArchivoService())->subir($request)), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar archivos de contratos ' . $e
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function showPorUsuario($id)
    {
        try {
            $usuario = Usuario::where('codigo_usuario', $id)->first();
            $contratos = $usuario->contratovigente;

            //return json_encode($usuario);

            return response()->json(["contrato" => ContratoResource::collection(
                $contratos
            )]);
        } catch (Exception $ex) {
            return response()->json(['error' => 'No se encontraron contratos asociados a este usuario'], 500);
        }
    }
    public function showPorToma($id)
    {


        try {
            $toma = Toma::where('codigo_toma', $id)->first();
            $contratos = $toma->contratovigente;
            foreach ($contratos as $c) {
                $c->toma;
            }
            return response()->json(["contrato" => ContratoResource::collection(
                $contratos
            )]);
        } catch (Exception $ex) {
            return response()->json(['error' => 'No se encontraron contratos asociados a este usuario'], 500);
        }
    }
    public function showPorFolio($folio, $ano) ///falta moverle
    {
        try {
            $usuario = Contrato::ConsultarPorFolio($folio, $ano);
            return response()->json(["contrato" => ContratoResource::collection(
                $usuario
            )]);
        } catch (Exception $ex) {
            return response()->json(['error' => 'No se encontraron contratos asociados a este usuario'], 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContratoRequest $request, Contrato $contrato)
    {
        $data = $request->validated();
        $contrato = (new ContratoService())->update($data['contrato']);
        return response()->json(["contrato" => new ContratoResource($contrato)], 200);
        try {
        } catch (Exception $ex) {
            return response()->json(['error' => 'No se pudo modificar el contrato, introduzca datos correctos'], 500);
        }
    }
    public function CerrarContrato(UpdateContratoRequest $request, Contrato $contrato) //TODO
    {

       

        try {
            DB::beginTransaction();
            $data = $request->validated()['contrato'];
            $contrato = Contrato::find($data['id']);
    
            if ($contrato['estatus'] != "pendiente de pago" || $contrato['estatus'] == "pagado" ) { //quitar pendiente de pago cuando se haga el cambio de estatus en caja
                if ($contrato['estatus']=="contratado"){
                    $error="No se puede cerrar un contrato que ya se encuentra concluido";
                }
                else{
                    $error="No se pudo cerrar el contrato, estado del contrato invalido: ".$contrato['estatus'];
                }
                return response()->json(['message' =>$error], 500);
            } else {
                $cargos = $contrato->cargosVigentes;
                //$cargoToma=Toma::find($contrato['id_toma'])->cargosVigentesConConcepto; TO DO
             
                if (count($cargos)!=0) {
                    return response()->json(['message' => 'No se pudo cerrar el contrato, tiene cargos pendientes: '. $cargos[0]->nombre ], 500);
                }
                /*
                else if(count($cargoToma)!=0){
                    //return $cargoToma[0]->nombre;
                    /// funciona distinto por los loles nomas
                    $error="No se pudo cerrar el contrato,la toma asociada tiene cargos pendientes: ";
                    $message=null;
                    foreach ($cargoToma as $cargos){
                        if (!$message){
                            $message=$message.$cargos->nombre;
                        }
                        else{
                            $message=$message.", ".$cargos->nombre;
                        }
                     
                    }
                    return response()->json(['message' =>  $error.$message ], 500);
                }
                    */ 
                else {
                    $toma = Toma::find($contrato['id_toma']); 
                    if ($toma['tipo_contratacion']=="pre-contrato"){
                        $toma->update(["tipo_contratacion"=>"normal"]);
                    }
                    if ($toma['c_agua']==null &&  $toma['c_alc']==null){
                        $toma['estatus'] = "pendiente de instalación";
                    }
                    //
                    if ($contrato['servicio_contratado'] == "agua") {
                        //$toma['c_agua'] == $contrato['id'];
                        $toma->update(["c_agua"=>$contrato['id']]);
                    } elseif ($contrato['servicio_contratado'] == "alcantarillado y saneamiento") {
                        $toma->update(["c_alc"=>$contrato['id'],
                        "c_san"=>$contrato['id']
                    ]);
                    }
                    if ($toma->estatus=="pendiente de inspeccion"){
                        $toma->estatus=="pendiente de instalacion";
                    }
                    $toma->save();
                    //$contrato = Contrato::find($data['id']);
                    $contrato['estatus']="contratado";
                    $contrato->save();
                  
                    DB::commit();
                    return response()->json(["contrato" => new ContratoResource($contrato),"toma"=>new TomaResource($toma)], 200);
             
                }
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['error' => 'No se pudo modificar el contrato, introduzca datos correctos'], 500);
        }
    }
    public function CambioNombreContrato(UpdateContratoRequest $request)
    {
        DB::beginTransaction();
        $data = $request->validated();
        $contrato = (new ContratoService())->update($data['contrato']);
        $toma = Toma::find($contrato['id_toma']);
        $conceptoCambio = ConceptoCatalogo::where('id', 32)->get();
        $Existe = Cargo::where('id_concepto', $conceptoCambio[0]['id'])->where('id_origen', $contrato['id'])->where('modelo_origen', 'contrato')->where('id_dueno', $toma['id'])->where('modelo_dueno', 'toma')->first();
        if ($Existe) {
        } else {
            $cargos = (new CargoService())->generarCargosToma($contrato, "contrato", $toma, "toma", $conceptoCambio);

            DB::commit();
            return response()->json(["contrato" => new ContratoResource($contrato), "cargos" => CargoResource::collection($cargos)]);
        }

        try {
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['error' => 'No se pudo modificar el contrato, introduzca datos correctos'], 200);
        }
    }
    public function destroy(Contrato $contrato, Request $request)
    {
        try {
            $contrato = Contrato::findOrFail($request["id"]);
            $contrato->update(['estatus'=>"cancelado"]);
            $contrato->save();
            return response()->json(['message' => 'Contrato cancelado correctamente'], 200);
        } catch (Exception $e) {

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

        try {
            return $cotizacion = Cotizacion::all();
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No hay cotizaciones.',
                'restore' => false
            ], 200);
        }
    }
    public function showCotizacion(Request $request)
    {
        try {
            $id_contratos = $request->all()['contrato'];
            $cotizacion = Contrato::find($id_contratos['id'])->cotizacionesVigentes;
           $detalles=$cotizacion->cotizacionesDetalles;
            $contrato=$cotizacion->contrato->tarifaContrato();
            $contrato->concepto;
            $newCollection = $detalles->map(function ($det) {
                $nuevo=$det;
                $nuevo['concepto']=$det->concepto;
                return $det;
            })->toArray();
            $newCollection[]=($contrato);
            //$cot=;
            return response()->json(["cotizacion" => $newCollection,"id_cotizacion"=> $cotizacion['id']]);
        } catch (Exception $ex) {
            return response()->json(['error' => 'No se encontraron cotizaciones asociadas a este contrato'], 200);
        }
    }
    public function crearCotizacion(Cotizacion $cotizacion, Request $request)
    {

        try {
            
            $contrato=Contrato::find($request->all()['id_contrato']);
            if ( $contrato->estatus=="contrato no factible"){
                throw new ErrorException('No se puede cotizar un contrato no factible', 500);
            }
            else{
                $concepto=$contrato->tarifaContrato();
                $concepto->concepto;
                return response()->json(["Tarifa"=> $concepto]) ;
            }
   
          
        } catch (Exception | ErrorException $ex) {

            $clase= get_class($ex);
            if ($clase=="ErrorException"){
                return response()->json(['error' => 'Error: '.$ex->getMessage()], 500);
            }
            else{
                return response()->json(['error' => 'No se pudo crear la cotización, introduzca datos correctos'], 500);
            }
        }
    }
    public function terminarCotizacion(Cotizacion $cotizacion, UpdateCotizacionRequest $request)
    {

        ////SI QUIERA SE USA????
        try {
            $data = $request->validated();
            $cotizacion = Cotizacion::find($data['id_cotizacion']);
            if ($cotizacion) {
                $cotizacion->update($data);
                $cotizacion->save();
                return new CotizacionResource($cotizacion);
            } else {
                //return $data;
                return response()->json(['message' => 'El contrato no tiene una cotización vigente'], 500);
            }
        } catch (Exception $ex) {
            return response()->json(['message' => 'La cotización no se puede cerrar'], 500);
        }
    }
    public function destroyCot(Cotizacion $cotizacion, Request $request)
    {
  
        
        try {
            DB::beginTransaction();
            $cotizacion = Cotizacion::findOrFail($request["id"]);
            $cotizacion->delete();
            DB::commit();
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        } catch (Exception $e) {

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
            return response()->json(['message' => 'La cotización ha sido restaurada.'], 200);
        }
    }

    /////COTIZACION DETALLE
    public function indexCot()
    {
        try {
            return CotizacionDetalleResource::collection(
                CotizacionDetalle::all()
            );
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No hay conceptos de cotizacion.',
                'restore' => false
            ], 200);
        }
    }
    public function crearCotDetalle(StoreCotizacionDetalleRequest $request)
    {
        
        try{
            DB::beginTransaction();
            $validado=$request->validated();
            $data = $validado['cotizacion_detalle'];
            $id_contrato=$validado['id_contrato'];
    
            $cotizacion = Contrato::find($id_contrato)->cotizacionesVigentes;
            if ($cotizacion){
                DB::rollBack();
                return response()->json(["message"=>"Este Contrato ya tiene una cotización vigente"],500);
            }
            $fecha=helperFechaAhora();
            $Cotiza['vigencia'] = Carbon::parse($fecha)->addMonths(1)->format('Y-m-d');
            $Cotiza['fecha_inicio'] = $fecha;
            $Cotiza['id_contrato'] = $id_contrato;
            $cotizacion=Cotizacion::create($Cotiza);
    
            $detalleCot = [];
    
            $contrato = Contrato::find($id_contrato);
            $tarifas = (new CotizacionService())->TarifaPorContrato($data,$cotizacion);
    
            $existe = Cargo::where('id_origen', $tarifas['id_contrato'])->where('modelo_origen', 'contrato')->first();
            $DetCollec=null;
            if ($existe) {
                DB::rollBack();
                return response()->json(['message' => 'No se puede generar un cargo para las cotizaciones porque ya existe un cargo de cotización asociado'], 500);
            }
            ////Ejemplo en optimizacion de consultas
            $conceptoIds = array_column($data, 'id_concepto');
            $conceptos = ConceptoCatalogo::whereIn('id', $conceptoIds)->get()->keyby('id');
            $tarifaConceptos = TarifaConceptoDetalle::where('id_tipo_toma', $contrato['tipo_toma'])
                                        ->whereIn('id_concepto', $conceptoIds)
                                        ->get()
                                        ->keyBy('id_concepto');
    
            foreach ($data as $detalle) {
                $id_concepto=$detalle['id_concepto'] ?? null;
                $monto = 0;
                if (!$id_concepto){
                    
                }
                else{
                    $concepto = $conceptos[$id_concepto];
                    if ($concepto['tarifa_fija'] == 1 && isset($tarifaConceptos[$id_concepto]) ) {
                        //$TarifaConcepto = TarifaConceptoDetalle::where('id_tipo_toma', $contrato['tipo_toma'])->where('id_concepto', $concepto['id'])->first();
                        $monto = $tarifaConceptos[$id_concepto]['monto'];;
                     
                    } else {
                        $monto = $detalle['monto'];
                        
                    }
                    $detalleCot[]=[
                        'id_cotizacion' => $cotizacion['id'],
                        'id_sector' => $detalle['id_sector'],
                        'id_concepto' => $detalle['id_concepto'],
                        'monto' => $monto
                    ];
        
                }
                
                $tarifas['montoDetalle'] += $monto;
            }
    
            if (!empty($detalleCot)) {
                CotizacionDetalle::insert($detalleCot);
                $DetCollec=CotizacionDetalle::where('id_cotizacion',$cotizacion['id'])->whereIn('id_concepto',$conceptoIds)->get();
                //return $DetCollec;
            }
            //Genera los cargos por cotizacion
    
            $tarifas['montoDetalle'] += $tarifas['monto'];
            $cargos = (new CotizacionService())->CargoContratos($tarifas);
    
    
          if ( $DetCollec){
            $detalle = CotizacionDetalleResource::collection(
                $DetCollec
            );
          }
          else{
            $detalle=$tarifas;
          }
         
            DB::commit();
            return response()->json([
                "contrato" => $cargos,
                "cotizacion_detalle" => $detalle
    
            ], 200);
        }
        catch(Exception $ex){
            DB::rollBack();
            return response()->json(["message"=>"No se pudo crear una cotización para este contrato"],500);
        }
       
    }
    public function destroyCotDetalle(CotizacionDetalle $Cotizacion, Request $request)
    {
        try {
            $Cotizacion = CotizacionDetalle::findOrFail($request["id"]);
            $Cotizacion->delete();
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        } catch (Exception $e) {

            return response()->json(['message' => 'error'], 500);
        }
    }
    public function restaurarCotDetalle(CotizacionDetalle $cotizacion, Request $request)
    {
        $cotizacion = CotizacionDetalle::withTrashed()->findOrFail($request->id);

        // Verifica si el registro está eliminado
        if ($cotizacion->trashed()) {
            // Restaura el registro
            $cotizacion->restore();
            return response()->json(['message' => 'El detalle ha sido restaurado.'], 200);
        }
    }
    public function showCotDetalle(Request $request)
    {
        try {
            $id_cotizaciones = $request['id_cotizaciones'];
            $cotizacion = new Collection();
            foreach ($id_cotizaciones as $det) {
                $cotizacion->push(Cotizacion::find($det)->cotizacionesDetalles);
            }

            return $cotizacion;
            /*
        return CotizacionDetalleResource::collection(
            $cotizacion
        );
        */
        } catch (Exception $ex) {
            return response()->json(['error' => 'No se encontraron cotizaciones asociadas a este contrato'], 500);
        }
    }
    public function ObtenerConceptos(Request $request)
    {
        $tipoToma = $request['id_tipo_toma'];
        return (new ContratoService())->ConceptosContratos();
    }

    public function generarContratoPdf($id)
    {
        try {
            $contrato = Contrato::findOrFail($id);
            $toma=$contrato->toma;
            $calleNotif=$toma->direccion_notificacion;
            $factibilidad=$toma->factibilidad;
            if ($factibilidad){
                $derechos=$factibilidad->derechos_conexion ?? 0;
                $data = [
                    'contrato_numero' => $contrato->folio_solicitud,
                    'direccion' => $contrato->toma->getDireccionCompleta(),
                    'numero_casa' => $contrato->numero_casa,
                    'servicio' => strtoupper($contrato->servicio_contratado),
                    'costo_conexion' => $derechos,
                    'recibo_numero' => $contrato->folio_solicitud,
                    'notificacion_calle_secundaria' => $calleNotif,
                    'notificacion_casa_numero' => $toma->numero_casa,
                    'nombre_usuario' => $contrato->toma->usuario->getNombreCompletoAttribute(),
                    'nombre_sistema' => 'Sistema Municipal de Agua Potable',
                    'fecha' => Carbon::createFromTimestamp($contrato->updated_at)->translatedFormat('j \d\e F \d\e Y')
                ];
            }
            else{
                $data = [
                    'contrato_numero' => $contrato->folio_solicitud,
                    'direccion' => $contrato->toma->getDireccionCompleta(),
                    'numero_casa' => $contrato->numero_casa,
                    'servicio' => strtoupper($contrato->servicio_contratado),
                    'costo_conexion' => 0,
                    'recibo_numero' => $contrato->folio_solicitud,
                    'notificacion_calle_secundaria' => $calleNotif,
                    'notificacion_casa_numero' => $toma->numero_casa,
                    'nombre_usuario' => $contrato->toma->usuario->getNombreCompletoAttribute(),
                    'nombre_sistema' => 'Sistema Municipal de Agua Potable',
                    'fecha' => Carbon::createFromTimestamp($contrato->updated_at)->translatedFormat('j \d\e F \d\e Y')
                ];
            }
          
          

            //$pdf = FacadePdf::loadView('contrato', $data);
            $pdf = FacadePdf::loadView('contrato', $data)
                ->setPaper('A4', 'portrait') // Tamaño de papel y orientación
                ->setOption('margin-top', 0)
                ->setOption('margin-right', 0)
                ->setOption('margin-bottom', 0)
                ->setOption('margin-left', 0);
            return $pdf->download('contrato.pdf');
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se pudo obtener el contrato' . $ex
            ], 500);
        }
    }

    public function FiltrosContratos(Request $request)
    {
        $data = $request->all();
        $filtros = (new ContratoService())->FiltrosContratos($data['filtros']);
        return response()->json(["tomas" => $filtros]);
    }
    public function PreContrato(Request $request)
    {

        try {
            DB::beginTransaction();
            $data = $request->all()['tomas'];
            $precontratos = (new ContratoService())->PreContrato($data);
            DB::commit();
            return response()->json(['tomas' => TomaResource::collection($precontratos)], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => 'No se pudo crear el precontrato para las tomas: '.$ex], 500);
        }
    }
}
