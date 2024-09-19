<?php
namespace App\Services\AtencionUsuarios;

use App\Http\Resources\DescuentoAsociadoResource;
use App\Models\DescuentoAsociado;
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
    /*  
    {
        "id_descuento" : 1,
        "id_modelo" : 1,
        "modelo_dueno" : "toma",
        "id_registra" : 1,
        "vigencia" : "2024-08-16",
        "estatus" : "no_vigente",
        "folio" : "JASNDKAD"
    }
    */
    try {
        $descuento = DescuentoAsociado::create($data);
        return response(new DescuentoAsociadoResource($descuento), 201);
    } catch (Exception $ex) {
       return response()->json(['error' => 'Ocurrio un error al registrar el descuento asociado. '], 500);
    }
}


}