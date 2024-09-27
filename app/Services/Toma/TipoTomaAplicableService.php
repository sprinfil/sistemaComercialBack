<?php
namespace App\Services\Toma;

use App\Http\Resources\TipoTomaAplicableResource;
use App\Models\ConceptoAplicable;
use App\Models\TipoTomaAplicable;
use Exception;
use Hamcrest\Core\HasToString;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoTomaAplicableService{

  public function StoreService(array $data)
  {
    try {
      $regPrevio = TipoTomaAplicable::withTrashed()->where('id_tipo_toma',$data['id_tipo_toma'])
        ->where('id_modelo',$data['id_modelo'])
        ->where('modelo_origen',$data['modelo_origen'])
        ->first();
      if ($regPrevio) {
        if ($regPrevio->trashed())
        {
          return response()->json([
            'message' => 'El tipo de toma aplicable ya existe pero ha sido eliminado, ¿Desea restaurarlo?',
            'restore' => true,
            'tipo_toma_aplicable_id' => $regPrevio->id
        ], 200);
        }
        return response()->json([
          'error' => 'El tipo de toma ya es aplicable a este modelo.'
          ],400); 
      }else{
        $tipoTomaAplicable = TipoTomaAplicable::create($data);
        
        return new TipoTomaAplicableResource($tipoTomaAplicable);
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error al registrar la configuracion del tipo de toma.'.$ex
        ],400); 
    }
    
  }

  public function busquedaPorModeloService(string $data)
  {
    try {
      
      if ($data == "ajuste_catalogo" || $data == "descuento_catalogo" || $data == "convenio_catalogo") {
        $tipoTomaAplicable = TipoTomaAplicable::where('modelo_origen',$data)
        ->with('tipoToma')
        ->with('origen')//cambiar este with cuando se arreglen las relaciones de tipotomaAplicable con sus origenes to do pendiente
        ->get();
        return json_encode($tipoTomaAplicable);
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

  public function busquedaPorTipoTomaService(string $data)
  {
    try {
      $tipoTomaAplicable = TipoTomaAplicable::where('id_tipo_toma',$data)
      ->with('tipoToma')
      ->with('origen')
      ->get();

      if ($tipoTomaAplicable) {
        return json_encode($tipoTomaAplicable);
      }
      else {
        return response()->json([
          'error' => 'No existe registro aplicable de su tipo de toma.'
          ],400); 
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error durante la busqueda.'.$ex
        ],400); 
    }
  }

 

  public function destroyTipoTomaAplicableService(string $data)
  {
    try {
      $tipoTomaAplicable = TipoTomaAplicable::where('id',$data)->first();
      if ($tipoTomaAplicable) {
        $tipoTomaAplicable->delete();
        return response()->json([
          'message' => 'El tipo de toma aplicable se ha eliminado correctamente.',
          'registro'=> $tipoTomaAplicable
          ]); 
      }
      else {
        return response()->json([
          'error' => 'No se encontro el registro a eliminar.'
          ],400); 
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error al eliminar el tipo de toma aplicable.'.$ex
        ],400); 
    }
  }
  
  public function restaurarTipoTomaAplicableService($data)
  {
    try {
      
      $tipoTomaAplicable = TipoTomaAplicable::onlyTrashed()->where('id',$data)->first();
      if ($tipoTomaAplicable) {
        $tipoTomaAplicable->restore();
        return response()->json([
          'message' => 'El tipo de toma Aplicable se ha restaurado correctamente.',
          'registro'=> $tipoTomaAplicable
          ]); 
      }
      else {
        return response()->json([
          'error' => 'No se encontro el registro a restaurar.'
          ],400); 
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error al restaurar el tipo de toma aplicable.'.$ex
        ],400); 
    }
  }
}