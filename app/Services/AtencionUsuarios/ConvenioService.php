<?php
namespace App\Services\AtencionUsuarios;

use App\Models\Toma;
use App\Models\Usuario;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConvenioService{

    public function BuscarConceptosConveniablesService(Request $data)
    {
      try {
      //  $toma = "";
       // $usuario = "";

        if ($data->tipo == "toma") {
          $toma = Toma::find($data->id);
         }
        if ($data->tipo == "usuario") {
          $usuario = Usuario::find($data->id);
         } 
         return $toma->cargosVigentesConConcepto;
      } catch (Exception $ex) {
        //throw $th;
      }
       

    }
}