<?php
namespace App\Services;

use App\Http\Resources\UsuarioResource;
use App\Models\Cargo;
use App\Models\Toma;
use App\Models\Usuario;
use Exception;
use App\Models\Contrato;
use App\Http\Resources\ContratoResource;
use App\Models\Calle;
use App\Models\Colonia;
use App\Models\ConceptoCatalogo;
use App\Models\Factibilidad;
use App\Models\Lectura;
use App\Models\Libro;
use App\Models\Secuencia_orden;
use FFI;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use MatanYadaev\EloquentSpatial\Objects\Point;

class ContratoService{

    public function Solicitud($servicio,$data,$toma,$solicitud, $EsPreContrato ){
        ////Crea la toma pendiente de inspección
        ////Crear solicitud de factibilidad
        $estado=$EsPreContrato ?? null;
        $toma=Toma::find($toma['id']);
        $c=new Collection();
        $factibilidad=new Collection();
        foreach ($servicio as $sev){
            $CrearContrato=$data;
            $CrearContrato['folio_solicitud']=Contrato::darFolio();
            $CrearContrato['servicio_contratado']=$sev;
            $CrearContrato['id_toma']=$toma['id'];
            $coordenada=$data['coordenada'] ?? null;
            if ($coordenada){
                $CrearContrato['coordenada']=$data['coordenada'][0]." ".$data['coordenada'][1];
            }
            else{
                $geometry = $toma->posicion;
                $jsonData = $geometry->toJson();

                $decodedData = json_decode($jsonData, true);

                $longitude = $decodedData['coordinates'][0];
                $latitude = $decodedData['coordinates'][1];
        
                $CrearContrato['coordenada']= $latitude." ".  $longitude;
  
            }

            if ( $estado && $estado=="pre-contrato"){
                //$CrearContrato['estatus']="pendiente de pago";
                $CrearContrato['estatus']="contratado";
            }
            else{
                if ($solicitud){
                    $CrearContrato['estatus']="pendiente de factibilidad";
                }
                else{
                    $CrearContrato['estatus']="inspeccionado";
                }
                
            }
            
            $cont=Contrato::create($CrearContrato);
            $c->push($cont);

            
            if  ($cont['estatus']=="contratado"){

                if ($cont['servicio_contratado']=="agua"){
                    $toma->update(["c_agua"=>$cont['id']]);
                }
                elseif ($cont['servicio_contratado']=="alcantarillado y saneamiento"){
                    $toma->update(["c_alc"=>$cont['id']]);
                    $toma->update(["c_san"=>$cont['id']]);
                }
            }
                
            $id_empleado_asigno=auth()->user()->operador->id;
            if ($solicitud==true){
                $factibilidad->push(Factibilidad::create([
                    "id_toma"=>$toma['id'],
                    "id_solicitante"=>$id_empleado_asigno,
                    "estado"=>"sin revisar"
                ]));
            }
          
        }
        return $c;
    }
    public function SolicitudToma($nuevaToma,$id_usuario,$data){
        $idToma=$data['id_toma'] ?? null;
        $existe=Toma::find($idToma) ?? null;
        if  (!$nuevaToma){
            if ($existe['id_usuario']!=$id_usuario && $existe['tipo_contratacion']!="pre-contrato"){
                return [ 'message' => 'Esta toma ya esta contratada a otro usuario'];
            }
            $toma=$existe;
            
            if ($toma['tipo_contratacion']=="pre-contrato"){
                $toma->update(["tipo_contratacion"=>"normal"]);
                $toma->update(["id_usuario"=>$data['id_usuario']]);
            }

        }
        else{
            $toma=$nuevaToma;
            $toma['id_tipo_toma']=$data['tipo_toma'];
            $toma['id_usuario']=$id_usuario;
            $toma['tipo_servicio']="lectura";
            //$toma['diametro_toma']="1 pulgada";
            $toma['tipo_contratacion']="normal";
            $toma['codigo_postal']=$data['codigo_postal'];
            $toma['numero_casa']=$data['num_casa'];
            $toma['calle']=$data['calle'];
            $toma['entre_calle_1']=$data['entre_calle1'] ?? null;
            $toma['entre_calle_2']=$data['entre_calle2'] ?? null;
            $toma['colonia']=$data['colonia'];
            $toma['localidad']=$data['localidad'];
            $toma['municipio']=$data['municipio'];
            $toma['clave_catastral']=$data['clave_catastral'];
            $coords=new Point($data['coordenada'][0],$data['coordenada'][1]);
            $toma['posicion']=$coords;
            $notificacion=$nuevaToma['direccion_notificacion'] ?? null;
            if (!$notificacion){
                $Calle=Calle::find($toma['calle'])->nombre;
                $Entre1=Calle::find($toma['entre_calle_1'])->nombre ?? null;
                $Entre2=Calle::find($toma['entre_calle_2'])->nombre ?? null;
                $colonia=Colonia::find($toma['colonia'])->nombre;
                
                $entrecalle1=$Entre1?"/".$Entre1: null;
                $entrecalle2= $Entre2?" & ".$Entre2: null;
                $toma['direccion_notificacion']=$Calle.$entrecalle1.$entrecalle2.", ".$colonia.", ".$toma['localidad'];
            }
            if ($existe){
                //$toma['estatus']="activa";
                $existe->update($toma);
                $existe->save();
                $toma= $existe;
            }
            else{
                $toma['estatus']="pendiente de inspección";
                $libro=Libro::find($toma['id_libro']);
                $toma['codigo_toma']=(new TomaService())->generarCodigoToma($libro);
                $toma=Toma::create($toma);
               
            }

        }
        return $toma;
    }
    /*
    public function Contratacion(){

        ///// crear

    }
        */
    public function ContratacionDesarrollador(array $tomas){
        ///Desarrollador y cargos de desarrollador
        foreach ($tomas as $toma){
            $toma['tipo_contratacion']="pre-contrato";
            $toma['estatus']="activa";
        }
    }

    public function BajaDefinitiva(){
        /////
    }
    public function getSolicitudesContrato(){
        ///Solicitudes de contratación
    }
    public function FiltrosContratos(array $filtros){
        $libro=$filtros['libro_id'] ?? null;
        $tipo_tomas=$filtros['tipo_tomas'] ?? null;
        $contrato_estatus=$filtros['contrato_estatus'] ?? null;
        $tipo_contratacion=$filtros['tipo_contratacion'] ?? null;
        $folio_solicitud=$filtros['folio_solicitud'] ?? null;
        $codigo_toma=$filtros['codigo_toma'] ?? null;

        $query=Toma::with('giroComercial','calle1','entre_calle2','entre_calle1','colonia1','usuario','tipoToma','libro','factibilidad','contrato.cotizaciones')
        ->when($tipo_tomas, function (Builder $q) use($tipo_tomas)  {
            $q->whereIn('id_tipo_toma',$tipo_tomas);///aplicar esto en OT

       })->when($contrato_estatus, function (Builder $q) use($contrato_estatus)  {
            $q->whereHas('contrato', function($a)use($contrato_estatus){

                $a->whereIn('estatus',$contrato_estatus);///aplicar esto en OT
                
            });
            
        })->when($tipo_contratacion, function (Builder $q) use($tipo_contratacion)  {
            $q->whereIn('tipo_contratacion',$tipo_contratacion);///aplicar esto en OT

        })->when($folio_solicitud, function (Builder $q) use($folio_solicitud)  {
            $q->whereHas('contrato', function($a)use($folio_solicitud){

                $a->where('folio_solicitud',$folio_solicitud);///aplicar esto en OT
                
            });
            
        })->when($codigo_toma, function (Builder $q) use($codigo_toma)  {
            $q->where('codigo_toma',$codigo_toma);///aplicar esto en OT

        })->orderBy('created_at','desc')
        ->get();

       return $query;
    }
    public function update(array $data){
        $contrato=Contrato::find($data['id']);
        $contrato->update($data);
        $contrato->save();
        return $contrato;
    }
    public function ConceptosContratos():ConceptoCatalogo{
        return ConceptoCatalogo::where('categoria','contrato')->get();
    }
    public function PreContrato($tomas){
        $PreContrato=new Collection();
        $orden=[];
        foreach ($tomas as $toma){
            $libro=Libro::where('nombre',$toma['nombre'])->first();
            if (!$libro){
                return response()->json(["error"=>"No existe libro con este nombre, introduzca un nombre de libro valido"],500);
            }
            else{
                $toma['id_libro']=$libro['id'];
                $toma['codigo_toma']=(new TomaService())->generarCodigoToma($libro);
                $toma['tipo_servicio']="lectura";
                $toma['tipo_contratacion']="pre-contrato";
                $toma['estatus']="activa";
                $coords=new Point($toma['posicion'][0],$toma['posicion'][1]);
                $toma['posicion']=$coords;
                unset($toma['nombre']);
                $nuevaToma=Toma::create($toma);
                $PreContrato->push($nuevaToma);

                $secuencia=$libro->secuenciasPadre;
              
                $orden[]=[
                    "id_secuencia"=>$secuencia->id,
                    "id_toma"=>$nuevaToma->id,
                    "numero_secuencia"=>0,
                ];
              
            }
         
        }
        $Secuencia_orden=Secuencia_orden::insert($orden);
        return $PreContrato;
    }
}