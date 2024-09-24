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

class ConceptoAplicableService{

  public function StoreService(array $data)
  {
    try {
      $regPrevio = ConceptoAplicable::where('id_concepto_catalogo',$data['id_concepto_catalogo'])
        ->where('id_modelo',$data['id_modelo'])
        ->where('modelo',$data['modelo'])
        ->first();
      if ($regPrevio) {
        return response()->json([
          'error' => 'El concepto ya es aplicable a este modelo.'
          ],400); 
      }else{
        $conceptoAplicable = ConceptoAplicable::create($data);
        return $conceptoAplicable;
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error al registrar el la configuracion del concepto.'.$ex
        ],400); 
    }
    
  }

  public function busquedaPorModeloService(string $data)
  {
    try {
      
      if ($data['modelo'] == "ajuste_catalogo" || $data['modelo'] == "descuento_catalogo" || $data['modelo'] == "convenio_catalogo") {
        $conceptosAplicables = ConceptoAplicable::where('modelo',$data['modelo'])
        ->with('concepto')
        ->with('conceptosAplicables')
        ->get();
        return $conceptosAplicables;
      }
      else{
        return response()->json([
          'error' => 'El modelo enviado no existe.'
          ],400); 
      }
     
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error durante la busqueda.'.$ex
        ],400); 
    }
  }

  public function busquedaPorConceptoService(string $data)
  {
    try {
      $conceptosAplicables = ConceptoAplicable::where('id_concepto_catalogo',$data['id_concepto_catalogo'])
      ->with('concepto')
      ->with('conceptosAplicables')
      ->get();

      if ($conceptosAplicables) {
        return $conceptosAplicables;
      }
      else {
        return response()->json([
          'error' => 'No existen cargos aplicables a este concepto.'
          ],400); 
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error durante la busqueda.'.$ex
        ],400); 
    }
  }

  public function updateConceptoAplicableService(array $data)
  {
    try {
      $conceptoAplicable = ConceptoAplicable::find($data['id'])->first();
      if ($conceptoAplicable) {
        $conceptoAplicable->update($data['concepto_aplicable'][0]);
      }
      else{
        return response()->json([
          'error' => 'No se encontro el registro a modificar.'
          ],400); 
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error durante la modificacion.'.$ex
        ],400); 
    }
  }

  public function deleteConceptoAplicableService(string $data)
  {
    try {
      $conceptoAplicable = ConceptoAplicable::find($data['id'])->first();
      if ($conceptoAplicable) {
        $conceptoAplicable->delete();
      }
      else {
        return response()->json([
          'error' => 'No se encontro el registro a eliminar.'
          ],400); 
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error al eliminar el concepto aplicable.'.$ex
        ],400); 
    }
  }
  
}