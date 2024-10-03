<?php
namespace App\Services\Catalogos;

use App\Http\Resources\MultaCatalogoResource;
use App\Models\MultaCatalogo;
use Exception;

class MultaCatalogoService{

    public function index ()
    {
        return response(MultaCatalogoResource::collection(
            MultaCatalogo::orderby('id', 'desc')->get()
        ), 200);
    }

    public function store ($data)
    {
       //Store del catalogo de multas.
    }

    public function show ($id)
    {
        try {
            $multa = MultaCatalogo::findOrFail($id);
            return response(new MultaCatalogoResource($multa), 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se pudo encontrar el descuento' .$ex->getMessage()
            ], 500);
        }
    }


}