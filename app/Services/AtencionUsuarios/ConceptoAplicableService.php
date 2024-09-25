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
      $regPrevio = ConceptoAplicable::withTrashed()->where('id_concepto_catalogo',$data['id_concepto_catalogo'])
        ->where('id_modelo',$data['id_modelo'])
        ->where('modelo',$data['modelo'])
        ->first();
      if ($regPrevio) {
        if ($regPrevio->trashed())
        {
          return response()->json([
            'message' => 'El Concepto aplicable ya existe pero ha sido eliminado, ¿Desea restaurarlo?',
            'restore' => true,
            'concepto_aplicable_id' => $regPrevio->id
        ], 200);
        }
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
      
      if ($data == "ajuste_catalogo" || $data == "descuento_catalogo" || $data == "convenio_catalogo") {
        $conceptosAplicables = ConceptoAplicable::where('modelo',$data)
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
      $conceptosAplicables = ConceptoAplicable::where('id_concepto_catalogo',$data)
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
      $conceptoAplicable = ConceptoAplicable::where('id',$data['id'])->first();
      if ($conceptoAplicable) {
        $conceptoAplicable->update($data['concepto_aplicable'][0]);
        return response()->json([
          'message' => 'El concepto Aplicable se ha modificado correctamente.',
          'registro'=> $conceptoAplicable
          ]); 
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

  public function destroyConceptoAplicableService(string $data)
  {
    try {
      $conceptoAplicable = ConceptoAplicable::where('id',$data)->first();
      if ($conceptoAplicable) {
        $conceptoAplicable->delete();
        return response()->json([
          'message' => 'El concepto Aplicable se ha eliminado correctamente.',
          'registro'=> $conceptoAplicable
          ]); 
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
  
  public function restaurarConceptoAplicableService($data)
  {
    try {
      
      $conceptoAplicable = ConceptoAplicable::onlyTrashed()->where('id',$data)->first();
      if ($conceptoAplicable) {
        $conceptoAplicable->restore();
        return response()->json([
          'message' => 'El concepto Aplicable se ha restaurado correctamente.',
          'registro'=> $conceptoAplicable
          ]); 
      }
      else {
        return response()->json([
          'error' => 'No se encontro el registro a restaurar.'
          ],400); 
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error al restaurar el concepto aplicable.'.$ex
        ],400); 
    }
  }
}