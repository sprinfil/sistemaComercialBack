<?php
namespace App\Services\Catalogos;

use App\Http\Resources\AjusteCatalogoResource;
use App\Models\AjusteCatalogo;
use COM;
use Exception;
use Illuminate\Http\Client\Request;

class AjusteCatalogoService{


    public function indexAjusteCatalogoService()
    {
       
       try {
        return AjusteCatalogoResource::collection(
            AjusteCatalogo::all()
        );
       } catch (Exception $ex) {

        return response()->json([
            'message' => 'No se encontraron registros de ajustes.'
        ], 200);
       }
        

    }

    public function storeAjusteCatalogoService(string $nombre,array $data)
    {

        try {       
        //Busca por nombre los eliminados
        $ajuste = AjusteCatalogo::withTrashed()->where('nombre' , $nombre)->first();
        if ($ajuste) {
            if ($ajuste->trashed()) {
                return response()->json([
                    'message' => 'El ajuste ya existe pero ha sido eliminado, ¿Desea restaurarlo?',
                    'restore' => true,
                    'ajuste_id' => $ajuste->id
                ], 200);
            }
            return response()->json([
                'message' => 'El ajuste ya existe',
                'restore' => false
            ], 200);
        }
        //Si no existe el ajuste, la crea
        if (!$ajuste) {
            $ajuste = AjusteCatalogo::create($data);
            return response(new AjusteCatalogoResource($ajuste), 201);
        }
        //$data = $request->validated();
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al registrar el ajuste.'
            ], 200);
        }
        
       
    }

    

    public function updateAjusteCatalogoservice(array $data, string $id)
    {
               //pendiente validacion que pemrite solucionar repetidos por modificaciones sin update request
        try {            
                $ajuste = AjusteCatalogo::find($id);
                if ($data != null) {
                    $ajuste->update($data);
                    $ajuste->save();
                    return new AjusteCatalogoResource($ajuste); 
                }
                else{
                    return response()->json([
                        'message' => 'Ocurrio un error al modificar el ajuste.'
                    ], 200);

                }                         
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al modificar el ajuste.'
            ], 200);
        }        
              
    }

    public function destroyAjusteCatalogoService(string $id)
    {
        try {
            $ajuste = AjusteCatalogo::find($id);
            $ajuste->delete();
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al eliminar el ajuste.'
            ], 200);
        }
        
    }

    public function restaurarAjusteCatalogoServicio (string $id)
    {
        try {
            $convenioCatalogo = AjusteCatalogo::withTrashed()->findOrFail($id);
            //Condicion para verificar si el registro esta eliminado
            if ($convenioCatalogo->trashed()) {
               //Restaura el registro
               $convenioCatalogo->restore();
               return response()->json(['message' => 'El ajuste ha sido restaurado']);
            }else{
                return response()->json(['message' => 'El ajuste no ha sido restaurado']);
            }
          
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al restaurar el ajuste.'
            ], 200);
        }
       
    }

}