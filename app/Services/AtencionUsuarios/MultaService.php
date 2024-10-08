<?php
namespace App\Services\AtencionUsuarios;

use App\Http\Resources\MultaResource;
use App\Models\Multa;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MultaService{

public function index ()
{
    return response(MultaResource::collection(
        Multa::orderby('id', 'asc')->get()
    ), 200);
}

public function store ($data)
{

}

public function show ($id)
{
    try {
        $multa = Multa::findOrFail($id);
        return response(new MultaResource($multa), 200);
    } catch (Exception $ex) {
        return response()->json([
            'error' => 'No se pudo encontrar la multa' .$ex->getMessage()
        ], 500);
    }
}

public function consultarporusuariotoma ($id_multado , $modelo_multado)
{
    try {
        //code
    } catch (ModelNotFoundException $ex) {
        return response()->json(['error' => 'Ocurrio un error al consultar la multa del usuario / toma'] , 500);
    }
}

}