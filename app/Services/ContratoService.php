<?php
namespace App\Services;

use App\Http\Resources\UsuarioResource;
use App\Models\Cargo;
use App\Models\Toma;
use App\Models\Usuario;
use Exception;
use App\Models\Contrato;
use App\Http\Resources\ContratoResource;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class ContratoService{

    public function Solicitud(){
        ////Crea la toma pendiente de inspección
        ////Crear solicitud de factibilidad

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

}