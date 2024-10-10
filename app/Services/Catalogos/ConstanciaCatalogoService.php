<?php
namespace App\Services\Catalogos;

use App\Http\Resources\ConstanciaCatalogoResource;
use App\Models\ConstanciaCatalogo;
use COM;
use Exception;
use Illuminate\Http\Client\Request;

class ConstanciaCatalogoService{


    public function indexconstanciaCatalogoService()
    {
       
       try {
        return ConstanciaCatalogoResource::collection(
            ConstanciaCatalogo::where('estado','activo')->orderby("id", "desc")->get()
        );
       } catch (Exception $ex) {

        return response()->json([
            'message' => 'No se encontraron registros de constancias.'
        ], 200);
       }
        

    }

    public function storeConstanciaCatalogoService(string $nombre,array $data)
    {

        try {       
        //Busca por nombre los eliminados
        $constancia = ConstanciaCatalogo::withTrashed()->where('nombre' , $nombre)->first();
        if ($constancia) {
            if ($constancia->trashed()) {
                return response()->json([
                    'message' => 'La constancia ya existe pero ha sido eliminada, ¿Desea restaurarla?',
                    'restore' => true,
                    'constancia_id' => $constancia->id
                ], 200);
            }
            return response()->json([
                'message' => 'La constancia ya existe',
                'restore' => false
            ], 200);
        }
        //Si no existe la constancia, la crea
        if (!$constancia) {
            $constancia = ConstanciaCatalogo::create($data);
            return response(new ConstanciaCatalogoResource($constancia), 201);
        }
       
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al registrar la constancia.'
            ], 200);
        }
        
       
    }

    

    public function updateAjusteCatalogoservice(array $data, string $id)
    {
               //pendiente validacion que permite solucionar repetidos por modificaciones sin update request
        try {            
                $ajuste = ConstanciaCatalogo::find($id);
                if ($data != null) {

                   $constancia = ConstanciaCatalogo::find($id);
                   $constancia->update($data);
                   $constancia->save();
                   return new ConstanciaCatalogoResource($constancia);
                }
                else{
                    return response()->json([
                        'message' => 'Ocurrio un error al modificar la constancia.'
                    ], 200);

                }                         
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al modificar la constancia.'
            ], 200);
        }        
              
    }

    public function destroyAjusteCatalogoService(string $id)
    {
        //$constancia = ConstanciaCatalogo::find($request["id"]);
        //$constancia->delete();
        try {
            
            $ajuste = ConstanciaCatalogo::find($id);
            $ajuste->delete();
            return response()->json([
                'message' => 'Se elimino la constancia.'
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al eliminar la constancia.'
            ], 200);
        }
        
    }

    public function restaurarConstanciaCatalogoServicio (string $id)
    {
        try {
            $constanciaCatalogo = ConstanciaCatalogo::withTrashed()->findOrFail($id);
           //Condicion para verificar si el registro esta eliminado
           if ($constanciaCatalogo->trashed()) {
              //Restaura el registro
              $constanciaCatalogo->restore();
              return response()->json(['message' => 'La constancia ha sido restaurada']);
        }
          
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al restaurar la constancia.'
            ], 200);         
        }
       
    }

}