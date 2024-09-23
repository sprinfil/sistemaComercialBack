<?php
namespace App\Services\AtencionUsuarios;

use App\Http\Requests\StoreConvenioRequest;
use App\Models\Abono;
use App\Models\Cargo;
use App\Models\CargosConveniado;
use App\Models\ConceptoAplicable;
use App\Models\ConceptoCatalogo;
use App\Models\Convenio;
use App\Models\Letra;
use App\Models\Pago;
use App\Models\Toma;
use App\Models\Usuario;
use App\Services\Caja\PagoService;
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
            
            $cargo_selecionado = Cargo::findOrFail($cargo['id_concepto']);
          
            if (count($temp) != 0) {
              $cargosAplicables[$nxt] = $cargo;
              $cargosAplicables[$nxt]['aplicable'] = "si";
              $cargosAplicables[$nxt]['rango_minimo'] = $temp[0]['rango_minimo'];
              $cargosAplicables[$nxt]['rango_maximo'] = $temp[0]['rango_maximo'];         
              $cargosAplicables[$nxt]['monto_pendiente'] = $cargo_selecionado->montoPendiente();             
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
          'Ocurrio un error durante la busqueda de cargos aplicables.'.$ex
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
            "monto_original_pendiente" =>  round($montoOriginalPendiente, 2),
            "monto_final_pendiente" =>  round($montoFinalPendiente, 2),
            "porcentaje_conveniado" => $cargo['porcentaje_conveniado'],
            "monto_conveniado" => round($montoConveniado, 2),
          ];

          CargosConveniado::create($cargosConveniados); 

          $montoConveniadoTotal += round($montoConveniado, 2);
          $montoFinalPendienteTotal += round($montoFinalPendiente, 2);

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
         $letrasCargo = [];

        for ($i=0; $i < $data['cantidad_letras']; $i++) { 

         $fechaCobro =Carbon::parse($fechaCobro)->format('Y-m-d');

         if ($i==($data['cantidad_letras']-1)) {
          $montoPorLetra = round($convenio->monto_total-$montoLetraSuma,2);
         }
          
          $letrasArray = [
            "id_convenio" => $convenio->id,
            "estado" => "pendiente",
            "monto" => $montoPorLetra,
            "vigencia" => $fechaCobro,
          ];
         
          $montoLetraSuma += $montoPorLetra;
          
          $letra = Letra::create($letrasArray);
          if ($i==0) {
            $letrasCargo =  $letra;
          }
          $ArregloLetras[$i] = $letra;
         
          $fechaCobro = Carbon::parse($fechaCobro);
          
          $fechaCobro->add($mensualidad);
         
        }
        
        //Aqui van los cargos to do pendiente el concepto que se le asigna al convenio debe estar definido en una configuracion 
        
        $concepto = ConceptoCatalogo::find(148);
        $fecha = helperFechaAhora();
        $fecha = Carbon::parse($fecha)->format('Y-m-d');
        
        $RegistroCargo = [
          "id_concepto" => $concepto->id,
          "nombre" => $concepto->nombre,

          "id_origen" => $letrasCargo['id'],
          "modelo_origen" => 'letra',

          "id_dueno" => $data['id_modelo'],
          "modelo_dueno" => $data['modelo_origen'],

          "monto" => $letrasCargo['monto'],
          "iva" => 0,
          "estado" => 'pendiente',
          "id_convenio" => null,

          "fecha_cargo" => $fecha,
          "fecha_liquidacion" => null,

        ];
        $cargo = Cargo::create($RegistroCargo);
        

        return json_encode($ArregloLetras);
       
      } catch (Exception $ex) {
        return response()->json([
          'Ocurio un error durante la realización del convenio.'.$ex
        ]);
      }
    }

    public function ConsultarConvenioService(Request $data)
    {
      try {
        $convenio = Convenio::where('modelo_origen',$data->modelo_origen)
        ->where('id_modelo',$data->id_modelo)
        ->where('estado','activo')
        ->with('letra')
        ->with('ConvenioCatalogo')
        ->first();
        if ($convenio == null) {
          return response()->json([
            'error'=>'No se encontro convenio asociado a la toma o el usuario seleccionado.'
          ]);
        }
        return json_encode($convenio);
      } catch (Exception $ex) {
        return response()->json([
          'error'=>'Ocurrio un error al consultar el convenio.'.$ex
        ],400);
      }
     
    }

    public function CancelarConvenioService(Request $data)
    {
      try {
        $convenio = Convenio::find($data['id_convenio']);

        if ($convenio->estado == "activo") {

          //Cargos originales asociados al convenio
          $cargos = Cargo::select('id')
          ->where('id_convenio',$convenio->id)
          ->get();
          //Letras del convenio
          $letras = Letra::select('id')
          ->where('id_convenio',$convenio->id)
          ->get();
          
          $arregloCargo = $cargos->toArray();
          $arregloLetra = $letras->toArray();

          //Aqui
         $cargosLetrasIds = Cargo::select('id')
          ->where('id_origen',$arregloLetra)
          ->where('modelo_origen','letra')
          ->get();

          $arregloCargosLetrasIds =  $cargosLetrasIds->toArray();

          $pagosIds = Abono::select('id_origen')
          ->where('modelo_origen',"pago")
          ->where('id_cargo',$arregloCargosLetrasIds)
          ->get();

          $arregloPagosIds = $pagosIds->toArray();

          //Cargos asociados a las letras del convenio, actualiza su estado
         $cargosLetrados = Cargo::where('id_origen',$arregloLetra)
          ->where('modelo_origen','letra') 
          ->update(['estado' => 'cancelado']);
         
          $convenioUpdt = [
            "estado" => "cancelado" 
          ];
          
          //Actualiza el estado del convenio, cargos originales y las letras del convenio
          $convenio->update($convenioUpdt);
          Cargo::whereIn('id', $arregloCargo)->update(['estado' => 'pendiente']);
          Letra::whereIn('id', $arregloLetra)->update(['estado' => 'cancelado']);

          Pago::whereIn('id',$arregloPagosIds)->update(['estado' => 'pendiente']);
          //falta arreglar el metodo de pagos
          $estatus = (new PagoService())->pagoAutomatico($convenio->id_modelo, $convenio->modelo_origen);
          return response()->json([
            'El convenio se ha cancelado correctamente.'
          ]);
          
        }else{
          return response()->json([
            'error'=>'No se encontro convenio seleccionado.'
          ],400);
        }
       
      } catch (Exception $ex) {
         return response()->json([
          'Ocurio un error durante la cancelación del convenio.'.$ex
        ],400);
      }
    }

    public function ConsultarListaConvenioService()
    {
      try {
        $convenio = Convenio::with('letra')
        ->with('ConvenioCatalogo')
        ->get();
        if ($convenio == null) {
          return response()->json([
            'error'=>'No se encontraron registros de convenios.'
          ]);
        }
        return json_encode($convenio);
      } catch (Exception $ex) {
        return response()->json([
          'error'=>'Ocurrio un error al consultar el convenio.'.$ex
        ],400);
      }
    }

}