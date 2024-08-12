<?php
namespace App\Services\Catalogos;

use App\Http\Resources\DescuentoCatalogoResource;
use App\Models\DescuentoCatalogo;
use COM;
use Exception;
use Illuminate\Http\Client\Request;

class DescuentoCatalogoService{


    public function indexDescuentoCatalogoService()
    {
       
       try {
        return response(DescuentoCatalogoResource::collection(
            DescuentoCatalogo::all()
        ),200);
       } catch (Exception $ex) {

        return response()->json([
            'message' => 'No se encontraron registros de descuentos.'
        ], 200);
       }
        

    }

    public function storeDescuentoCatalogoService(string $nombre,array $data)
    {

        try {       
            //Busca por nombre los eliminados
            $descuento = DescuentoCatalogo::withTrashed()->where('nombre' , $nombre)->first();
            if ($descuento) {
               if ($descuento->trashed()) {
                  return response()->json([
                     'message' => 'El descuento ya existe pero ha sido eliminada, ¿Desea restaurarla?',
                     'restore' => true,
                     'descuento_id' => $descuento->id
                    ], 200);
                }
                return response()->json([
                  'message' => 'El descuento ya existe',
                  'restore' => false
                ], 200);
            }
            //Si no existe el descuento, lo crea
            if (!$descuento) {
              $descuento = DescuentoCatalogo::create($data);
              return response(new DescuentoCatalogoResource($descuento), 201);
            }
       
         } catch (Exception $ex) {
             return response()->json([
                 'message' => 'Ocurrio un error al registrar el descuento.'
             ], 200);
         }
              
    }

    public function showDescuentoCatalogoService(string $id)
    {
        try {
            $descuento = DescuentoCatalogo::findOrFail($id);
            return response(new DescuentoCatalogoResource($descuento), 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se pudo encontrar el descuento'
            ], 500);
        }
    }

    

    public function updateDescuentoCatalogoservice(array $data, string $id)
    {
               //pendiente validacion que permite solucionar repetidos por modificaciones sin update request
        try {            
                
                if ($data != null) {
                 
                    $descuento = DescuentoCatalogo::findOrFail($id);
                    $descuento->update($data);
                    $descuento->save();
                    return response(new DescuentoCatalogoResource($descuento), 200);
                }
                else{
                    return response()->json([
                        'error' => 'No se pudo editar el descuento'
                    ], 500);

                }                         
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al modificar el descuento.'
            ], 200);
        }        
              
    }

    public function destroyDescuentoCatalogoService(string $id)
    {      
        try {          
            $descuento = DescuentoCatalogo::findOrFail($id);
            $descuento->delete();
            return response("Descuento eliminado con exito",200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al borrar el descuento.'
            ], 500);
        }      
    }

    public function restaurarDescuentoCatalogoService (string $id)
    {
        try {
            $catalogoDescuento = DescuentoCatalogo::withTrashed()->findOrFail($id);
            //Condicion para verificar si el registro esta eliminado
            if ($catalogoDescuento->trashed()) {
              //Restaura el registro
              $catalogoDescuento->restore();
            return response()->json(['message' => 'El descuento ha sido restaurado' , 200]);
        }
          
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al restaurar el descuento.'
            ], 200);         
        }
       
    }

}