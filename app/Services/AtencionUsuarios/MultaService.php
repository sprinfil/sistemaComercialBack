<?php
namespace App\Services\AtencionUsuarios;

use App\Http\Resources\MultaResource;
use App\Models\Multa;
use App\Models\MultaCatalogo;
use App\Models\Toma;
use App\Models\Usuario;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MultaService{

public function index ()
{
    return response(MultaResource::collection(
        Multa::orderby('id', 'asc')->get()
    ), 200);
}

public function store ($data,$codigo_toma)
{
    try {
        //Para levantar una multa, se necesita buscar el codigo de la toma. 
        //$codigo_toma = $data['codigo_toma'];
        $cod_toma = Toma::where('codigo_toma' , $codigo_toma)->first();
        $catalogo_multa = MultaCatalogo::where('id', $data['id_catalogo_multa'])
        ->first();
        //Cuenta cuantas multas tiene registrada la toma. 
        //$total_multas = Multa::where('id_multado', $cod_toma->id)->count();
        if (!$catalogo_multa) {
            return response()->json([
                'message' => 'No se encontro la multa en el catalogo.'
            ], 404);
        }
        if ($cod_toma) {
            $data['id_multado'] = $cod_toma->id;
            $multa = Multa::create($data);
            return new MultaResource($multa);
        }
        else{
            return response()->json([
                'message' => 'No se encontro la toma.'
            ], 404);
        }

    } catch (Exception $ex) {
        return response()->json([
            'error' => 'Ocurrio un error al levantar la multa. ' .$ex->getMessage()
        ],500);
    }
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

public function consultarmulta ($modelo_multado , $id_multado, $id_catalogo_multa, $codigo_usuario)
{
    try {
        $filtro = Multa::with('origen')
        ->when($id_multado, function ($query, $id_multado) {
            return $query->where('id_multado', $id_multado);
        })
        ->when($modelo_multado, function ($query, $modelo_multado) {
            return $query->where('modelo_multado', $modelo_multado);
        })
        ->when($id_catalogo_multa, function ($query, $id_catalogo_multa){
            return $query->where('id_catalogo_multa' , $id_catalogo_multa);
        })
        ->orderBy('id', 'desc')
        ->get();
        $usuario = Usuario::with(['toma.dueno_multa' => function ($query) use ($id_catalogo_multa){
            if ($id_catalogo_multa) {
                $query->where('id_catalogo_multa' , $id_catalogo_multa);
            }
        }])
        ->when($codigo_usuario , function ($query , $codigo_usuario){
            return $query->where('codigo_usuario' , $codigo_usuario);
        })
        ->get();
    if ($filtro->isEmpty()) {
        return response()->json(['message' => 'No se encontraron resultados'], 404);
    } else {
        if ($filtro['origen' == 'toma']) {
            return $usuario;
         }
        return $filtro;
    }
    } catch (ModelNotFoundException $ex) {
        return response()->json(['error' => 'Ocurrio un error al consultar la multa del usuario / toma' . $ex] , 500);
    }
}

}