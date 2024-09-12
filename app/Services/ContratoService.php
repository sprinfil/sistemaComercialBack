<?php
namespace App\Services;

use App\Http\Resources\UsuarioResource;
use App\Models\Cargo;
use App\Models\Toma;
use App\Models\Usuario;
use Exception;
use App\Models\Contrato;
use App\Http\Resources\ContratoResource;
use App\Models\ConceptoCatalogo;
use App\Models\Factibilidad;
use App\Models\Lectura;
use App\Models\Libro;
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
            if ( $estado && $estado=="pre-contrato"){
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
                return response()->json([
                    'message' => 'Esta toma esta contratada a otro usuario'
                ], 500);
            }
        }
        else{
            $toma=$nuevaToma;
            $toma['id_tipo_toma']=$data['tipo_toma'];
            $toma['id_usuario']=$id_usuario;
            $toma['tipo_servicio']="lectura";
            $toma['tipo_contratacion']="normal";
            $toma['codigo_postal']=$data['codigo_postal'];
            $toma['numero_casa']=$data['num_casa'];
            $toma['calle']=$data['calle'];
            $toma['entre_calle1']=$data['entre_calle1'] ?? null;
            $toma['entre_calle2']=$data['entre_calle2'] ?? null;
            $toma['colonia']=$data['colonia'];
            $toma['localidad']=$data['localidad'];
            $toma['municipio']=$data['municipio'];
            $notificacion=$toma['calle_notificaciones'] ?? null;
            if (!$notificacion){
                $entrecalle1=$toma['entre_calle1']?"/".$toma['entre_calle1']: null;
                $entrecalle2= $toma['entre_calle2']?" & ".$toma['entre_calle2']: null;
                $toma['direccion_notificacion']=$toma['calle'].$entrecalle1.$entrecalle2.", ".$toma['colonia'].", ".$toma['localidad'];
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

        $query=Toma::with('usuario','tipoToma','libro','contrato.factibilidad','contrato.cotizaciones')
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
        ->take(100)
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
                $PreContrato->push(Toma::create($toma));
            }
         
        }
        return $PreContrato;
    }
}