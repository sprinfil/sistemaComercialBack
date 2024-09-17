<?php
namespace App\Services\AtencionUsuarios;

use App\Http\Requests\StoreConvenioRequest;
use App\Models\Cargo;
use App\Models\CargosConveniado;
use App\Models\ConceptoAplicable;
use App\Models\Convenio;
use App\Models\Letra;
use App\Models\Toma;
use App\Models\Usuario;
use Carbon\Carbon;
use DateInterval;
use Exception;
use Hamcrest\Core\HasToString;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConvenioService{

    public function BuscarConceptosConveniablesService(Request $data)
    {
      try { //falta condicion para que no pueda crear un convenio si tiene algun convenio ya activo
        //  $conceptoAplicable = [];
          $cargos = Cargo::where('modelo_dueno',$data->tipo)
          ->where('id_dueno',$data->id)
          ->where('estado','pendiente')
          ->where('id_convenio',null)
          ->get();
          $cargos = $cargos->toArray();
          $cargosAplicables = [];
          $nxt = 0;
          $temp = [];
          foreach ($cargos as $cargo) //pendiente eliminar consultas en ciclos
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
        ],500);
      }
    }

    public function RegistrarConvenioService(array $data)
    {
      try {

        $fecha = helperFechaAhora();
        $fechaCobro =Carbon::parse($fecha)->format('Y-m-d');

        //El porcentaje que se convenio de los cargos
        $porcentajeConveniado = $data['porcentaje_conveniado'];
        //La informacion del registro convenio, el resto se calcula durante el proceso
        $convenio = [
        "id_convenio_catalogo" => $data['id_convenio_catalogo'], 
        "monto_conveniado" => null, //Este elemento es calculado posteriormente
        "monto_total" => null, //Este elemento es calculado posteriormente
        "periodicidad" => "mensual",
        "cantidad_letras" => $data['cantidad_letras'],
        "estado" => "activo",
        "comentario" => $data['comentario']  
        ];
        //La lista de cargos que se desean conveniar
        $cargoTemp = "";
        $cargos = $data['cargos_conveniados'];
        $cargosConveniados = [];

        foreach($cargos as $cargo) //pendiente eliminar consultas en ciclos
        {
          $cargoTemp = Cargo::find($cargo['id']);
         
          $temp = ConceptoAplicable::where('id_concepto_catalogo',$cargoTemp->id_concepto)
          ->where('modelo','convenio_catalogo')
          ->where('id_modelo',$data['id_convenio_catalogo'])
          ->get();

          if (count($temp) == 0) {      
            return response()->json([
              'error'=>'El convenio seleccionado no es compatible con los cargos seleccionados.'
            ],400);     
          }
          if ($cargoTemp->estado == "conveniado") {
            return response()->json([
              'error'=>'Un cargo no puede pertenecer a varios convenios.'
            ],400);
          }
          
        }

        $convenio = Convenio::create($convenio); //Esta comentado para que deje de crear convenios en lo que avanzo

        $montoConveniadoTotal = 0;
        $montoFinalPendienteTotal = 0;
       
        $cargoUpdt = [
          "estado" => "conveniado", 
          "id_convenio" => $convenio->id //Este elemento es calculado posteriormente
        ];

        foreach($cargos as $cargo)
        {
          //Pendiente actualziar el estado del cargo a conveniado y meterle al id de convenio
          $cargoTemp = Cargo::find($cargo['id']); //obtengo los datos del cargo
          $montoOriginalPendiente = $cargoTemp->montoPendiente(); //este es el monto original pendiente
          $montoConveniado = ($porcentajeConveniado * $montoOriginalPendiente)/100; //monto del convenio aplicando el porcentaje conveniado
          $montoFinalPendiente = $montoOriginalPendiente - $montoConveniado; // Es el monto original restandole el convenio aplicado

          $cargoTemp->update($cargoUpdt);

          
          $cargosConveniados =[
            "id_cargo" => $cargo['id'],
            "id_convenio" => $convenio->id,
            "monto_original_pendiente" => $montoOriginalPendiente,
            "monto_final_pendiente" => $montoFinalPendiente,
            "porcentaje_conveniado" => $porcentajeConveniado,
            "monto_conveniado" => $montoConveniado,
          ];

          CargosConveniado::create($cargosConveniados); 

          $montoConveniadoTotal += $montoConveniado;
          $montoFinalPendienteTotal += $montoFinalPendiente;

        }

        $convenioMontos = [
          "monto_conveniado" => $montoConveniadoTotal,
          "monto_total" => $montoFinalPendienteTotal,
        ];

        $convenio->update($convenioMontos); 
        $convenio->save();

        $montoPorLetra = $convenio->monto_total/$convenio->cantidad_letras;

         $mensuaildad = new DateInterval('P1M');  //Sumar meses o años: Puedes usar P1M para un mes o P1Y para un año en lugar de días.

        for ($i=0; $i < $data['cantidad_letras']; $i++) { 

         $fechaCobro =Carbon::parse($fechaCobro)->format('Y-m-d');
          
          $letrasArray = [
            "id_convenio" => $convenio->id,
            "estado" => "pendiente",
            "monto" => $montoPorLetra,
            "vigencia" => $fechaCobro,
          ];
          
          $letra = Letra::create($letrasArray);
          $ArregloLetras[$i] = $letrasArray;
         
          $fechaCobro = Carbon::parse($fechaCobro);
          
          $fechaCobro->add($mensuaildad);
         
        }
      
       // return $ArregloLetras;
       
      } catch (Exception $ex) {
        return response()->json([
          'Ocurio un error durante la realización del convenio.'.$ex
        ]);
      }
    }
}