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
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class ContratoService{

    public function Solicitud($servicio,$data,$toma){
        ////Crea la toma pendiente de inspección
        ////Crear solicitud de factibilidad

        $c=new Collection();
        foreach ($servicio as $sev){
            $CrearContrato=$data;
            $CrearContrato['folio_solicitud']=Contrato::darFolio();
            $CrearContrato['servicio_contratado']=$sev;
            $CrearContrato['id_toma']=$toma['id'];
            $c->push(Contrato::create($CrearContrato));
        }
        return $c;
    }
    public function SolicitudToma($nuevaToma,$id_usuario,$data){
        if  (!$nuevaToma){
            $toma=Toma::find($data['id_toma']);
            if ($toma['id_usuario']!=$id_usuario){
                return response()->json([
                    'message' => 'Esta toma esta contratada a otro usuario'
                ], 500);
            }
        }
        else{
            $toma=$nuevaToma;
            $toma['id_tipo_toma']=$data['tipo_toma'];
            $toma['id_usuario']=$id_usuario;
            $toma['estatus']="pendiente de inspección";
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
            $toma=Toma::create($toma);
        }
        return $toma;
    }
    public function Contratacion(){

        ///// crear

    }
    public function ContratacionDesarrollador(){
        ///Desarrollador y cargos de desarrollador
        ///
    }

    public function BajaDefinitiva(){
        /////
    }
    public function getSolicitudesContrato(){
        ///Solicitudes de contratación
    }
    public function FiltrosContratos(array $filtros){

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

}