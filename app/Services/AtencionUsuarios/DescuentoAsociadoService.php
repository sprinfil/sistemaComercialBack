<?php
namespace App\Services\AtencionUsuarios;

use App\Http\Resources\DescuentoAsociadoResource;
use App\Models\Archivo;
use App\Models\DescuentoAsociado;
use App\Models\DescuentoCatalogo;
use Exception;
use Illuminate\Http\Request;
class DescuentoAsociadoService {

public function index ()
{
    try {
        return response(DescuentoAsociadoResource::collection(
            DescuentoAsociado::all()
        ),200);
    } catch (Exception $ex) {
        return response()->json(['error' => 'Ocurrio un error al buscar los descuentos asociados'] , 500);
    }
}

public function store (array $data)
{

    try {
        $folio = $data['folio'];
        $id_evidencia = $data['id_evidencia'];

        $id_modelo = $data['id_modelo'];
        $modelo_dueno = $data['modelo_dueno'];

        $id_descuento = $data['id_descuento'];

        //Si el id_modelo / modelo dueño existen en descuentos asociados
        $dueno = DescuentoAsociado::where('id_modelo', $id_modelo)
        ->Where('modelo_dueno', $modelo_dueno)
        ->exists();

        $descuentos = DescuentoAsociado::where('folio' , $folio)
        ->orWhere('id_evidencia' , $id_evidencia)
        ->exists();

            if ($dueno) {
            return response()->json(['message'=>'Ya existe un descuento asociado'] , 400);
            }
                if ($descuentos) {
               return response()->json(['message'=>'Ya existe un folio o una evidencia'] , 400);
                }
                else{
                $descuento = DescuentoAsociado::create($data);
            }
        return response(new DescuentoAsociadoResource($descuento), 201);
    } catch (Exception $ex) {
       return response()->json(['error' => 'Ocurrio un error al registrar el descuento asociado. ' . $ex], 500);
    }
}

public function filtro($id_modelo, $modelo_dueno)
{
    try {
        $filt = DescuentoAsociado::where('modelo_dueno' , $modelo_dueno)
        ->orWhere('id_modelo' , $id_modelo)
        ->get();
        if (!$filt) {
            return response()->json(['message' => 'No se encontraron resultados']);
        }
        return $filt;
    } catch (Exception $ex) {
        return response()->json(['error' => 'Ocurrio un error al consultar el descuento asociado. ' . $ex], 500);
    }
}


}