<?php
namespace App\Services\AtencionUsuarios;

use App\Models\Cargo;
use App\Models\ConceptoAplicable;
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
        //  $conceptoAplicable = [];
          $cargos = Cargo::where('modelo_dueno',$data->tipo)
          ->where('id_dueno',$data->id)
          ->where('estado','pendiente')
          ->get();
          $cargos = $cargos->toArray();
          $cargosAplicables = [];
          $nxt = 0;
          $temp = [];
          foreach ($cargos as $cargo)
          {
            $temp = ConceptoAplicable::where('id_concepto_catalogo',$cargo['id_concepto'])
            ->where('modelo','convenio_catalogo')
            ->where('id_modelo',$data['id_convenio_catalogo'])
            ->get();
           
            if (count($temp) != 0) {
              $cargosAplicables[$nxt] = $cargo;
              $cargosAplicables[$nxt]['aplicable'] = "si";
              $cargosAplicables[$nxt]['rango_minimo'] = $temp[0]['rango_minimo'];
              $cargosAplicables[$nxt]['rango_maximo'] = $temp[0]['rango_maximo'];             
            }
            else{
              $cargosAplicables[$nxt] = $cargo;
              $cargosAplicables[$nxt]['aplicable'] = "No";
            }          
            $nxt++;
          }

         return json_encode($cargosAplicables);

      } catch (Exception $ex) {
        return response()->json([
          'Ocurio un error durante la busqueda de cargos aplicables.'
        ]);
      }
    }
}