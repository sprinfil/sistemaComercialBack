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
      try { 

          if ($data->tipo == "toma") {
            $conveniado = Toma::find($data->id);
            $conveniado = $conveniado->conveniosActivos;
                       
          }
          if ($data->tipo == "usuario") {
            $conveniado = Usuario::find($data->id);
            $conveniado = $conveniado->conveniosActivos;
            
          }
          if (count($conveniado) != 0) {
            return response()->json([
              'El usuario o la toma seleccionada ya cuenta con un convenio.'
            ],400);
          }
          $cargos = Cargo::where('modelo_dueno',$data->tipo)
          ->where('id_dueno',$data->id)
          ->where('estado','pendiente')
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
          'Ocurrio un error durante la busqueda de cargos aplicables.'
        ],500);
      }
    }

    public function RegistrarConvenioService(array $data)
    {
      try {

        $fecha = helperFechaAhora();
        $fechaCobro =Carbon::parse($fecha)->format('Y-m-d');
        $montoLetraSuma = 0;
        //El porcentaje que se convenio de los cargos
        
        //La informacion del registro convenio, el resto se calcula durante el proceso
        $convenio = [
        "id_convenio_catalogo" => $data['id_convenio_catalogo'], 
        "id_modelo"=>$data['id_modelo'],
        "modelo_origen"=>$data['modelo_origen'],
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

        $convenio = Convenio::create($convenio); //Crea el registro de convenio

        $montoConveniadoTotal = 0;
        $montoFinalPendienteTotal = 0;
       
        //Registro para actualizar los cargos
        $cargoUpdt = [
          "estado" => "conveniado", 
          "id_convenio" => $convenio->id //Este elemento es calculado posteriormente
        ];

        foreach($cargos as $cargo)
        {
          $cargoTemp = Cargo::find($cargo['id']); //obtengo los datos del cargo
          $montoOriginalPendiente = $cargoTemp->montoPendiente(); //este es el monto original pendiente
          $montoConveniado = ($cargo['porcentaje_conveniado'] * $montoOriginalPendiente)/100; //monto del convenio aplicando el porcentaje conveniado
          $montoFinalPendiente = $montoOriginalPendiente - $montoConveniado; // Es el monto original restandole el convenio aplicado
          //Actualiza los cargos
          $cargoTemp->update($cargoUpdt);

          //Registro de cargos conveniados
          $cargosConveniados =[
            "id_cargo" => $cargo['id'],
            "id_convenio" => $convenio->id,
            "monto_original_pendiente" => $montoOriginalPendiente,
            "monto_final_pendiente" => $montoFinalPendiente,
            "porcentaje_conveniado" => $cargo['porcentaje_conveniado'],
            "monto_conveniado" => $montoConveniado,
          ];

          CargosConveniado::create($cargosConveniados); 

          $montoConveniadoTotal += $montoConveniado;
          $montoFinalPendienteTotal += $montoFinalPendiente;

        }
        //Actualizar el registro de convenio con los montos
        $convenioMontos = [
          "monto_conveniado" => $montoConveniadoTotal,
          "monto_total" => $montoFinalPendienteTotal,
        ];

        $convenio->update($convenioMontos);  //redondea las variables desde aqui
        $convenio->save();

        //Registra las letras
        $montoPorLetra = round($convenio->monto_total/$convenio->cantidad_letras, 2);

         $mensualidad = new DateInterval('P1M');  //Sumar meses o años: Puedes usar P1M para un mes o P1Y para un año en lugar de días.

        for ($i=0; $i < $data['cantidad_letras']; $i++) { 

         $fechaCobro =Carbon::parse($fechaCobro)->format('Y-m-d');

         if ($i==($data['cantidad_letras']-1)) {
          $montoPorLetra = $convenio->monto_total-$montoLetraSuma;
         }
          
          $letrasArray = [
            "id_convenio" => $convenio->id,
            "estado" => "pendiente",
            "monto" => $montoPorLetra,
            "vigencia" => $fechaCobro,
          ];
          $montoLetraSuma += $montoPorLetra;
          
          $letra = Letra::create($letrasArray);
          $ArregloLetras[$i] = $letra;
         
          $fechaCobro = Carbon::parse($fechaCobro);
          
          $fechaCobro->add($mensualidad);
         
        }
        
        //Aqui van los cargos to do pendiente 
        $RegistroCargo = [
          "id_concepto" => $convenio->id,
          "nombre" => $convenio->id,
          "id_origen" => $convenio->id,
          "modelo_origen" => 'letra',
          "id_dueno" => $convenio->id,
          "modelo_dueno" => $convenio->id,
          "monto" => $convenio->id,
          "iva" => $convenio->id,
          "estado" => $convenio->id,
          "id_convenio" => $convenio->id,
          "fecha_cargo" => $convenio->id,
          "fecha_liquidacion" => $convenio->id,

        ];

        return json_encode($ArregloLetras);
       
      } catch (Exception $ex) {
        return response()->json([
          'Ocurio un error durante la realización del convenio.'.$ex
        ]);
      }
    }

    public function ConsultarConvenioService(Request $data)
    {
      $convenio = Convenio::where('modelo_origen',$data->modelo_origen)
      ->where('id_modelo',$data->id_modelo)
      ->where('estado','activo')
      ->with('letra')
      ->first();
      return json_encode($convenio);
    }

    public function CancelarConvenioService(Request $data)
    {
      try {
        $convenio = Convenio::where('modelo_origen',$data->modelo_origen)
        ->where('id_modelo',$data->id_modelo)
        ->where('estado','activo')
        ->first();
        $cargos = Cargo::select('id')
        ->where('id_convenio',$convenio->id)
        ->get();
        $letras = Letra::select('id')
        ->where('id_convenio',$convenio->id)
        ->get();

        $arregloCargo = $cargos->toArray();
        $arregloLetra = $letras->toArray();

        $convenioUpdt = [
          "estado" => "cancelado" 
        ];

        $convenio->update($convenioUpdt);
        Cargo::whereIn('id', $arregloCargo)->update(['estado' => 'pendiente']);
        Letra::whereIn('id', $arregloLetra)->update(['estado' => 'cancelado']);

        return response()->json([
          'El convenio se ha cancelado correctamente.'
        ]);
        
      } catch (Exception $ex) {
         return response()->json([
          'Ocurio un error durante la cancelación del convenio.'.$ex
        ]);
      }
    }

}