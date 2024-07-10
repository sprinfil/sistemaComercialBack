<?php

namespace App\Http\Controllers\Api;

use App\Models\ConceptoCatalogo;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConceptoResource;
use App\Http\Requests\StoreConceptoCatalogoRequest;
use App\Http\Requests\UpdateConceptoCatalogoRequest;
use Illuminate\Http\Request;

class ConceptoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', ConceptoCatalogo::class);
        return ConceptoResource::collection(
            ConceptoCatalogo::orderby("id", "desc")->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConceptoCatalogo $conceptoCatalogo, StoreConceptoCatalogoRequest $request)
    {
        $this->authorize('create', ConceptoCatalogo::class);
        //Valida el store
        $data = $request->validated();
        //Busca por nombre a los operadores eliminados
        $conceptoCatalogo = ConceptoCatalogo::withTrashed()->where('nombre', $request->input('nombre'))->first();

        //Validacion en caso de que el operador ya este registrado en le base de datos
        if ($conceptoCatalogo) {
            if ($conceptoCatalogo->trashed()) {
                return response()->json([
                    'message' => 'El concepto ya existe pero ha sido eliminado. ¿Desea restaurarlo?',
                    'restore' => true,
                    'concepto_id' => $conceptoCatalogo->id
                ], 200);
            }
            return response()->json([
                'message' => 'El concepto ya existe.',
                'restore' => false
            ], 200);
        }

        //Si el operador no existe, lo crea
        if(!$conceptoCatalogo)
        {
            $conceptoCatalogo = ConceptoCatalogo::create($data);
            return new ConceptoResource($conceptoCatalogo);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ConceptoCatalogo $conceptoCatalogo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConceptoCatalogoRequest $request, ConceptoCatalogo $conceptoCatalogo)
    {
        $this->authorize('update', ConceptoCatalogo::class);
        $data = $request->validated();
        $conceptoCatalogo = ConceptoCatalogo::find($request["id"]);
        $conceptoCatalogo->update($data);
        $conceptoCatalogo->save();
        return new ConceptoResource($conceptoCatalogo);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConceptoCatalogo $conceptoCatalogo,Request $request)
    {
        $this->authorize('delete', ConceptoCatalogo::class);
        try
        {
            $conceptoCatalogo = ConceptoCatalogo::findOrFail($request->id);
            $conceptoCatalogo->delete();
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        }
        catch (\Exception $e) {

            return response()->json(['message' => 'Algo fallo'], 500);
        }
    }




    public function restaurarDato(ConceptoCatalogo $conceptoCatalogo, Request $request)
    {

        $conceptoCatalogo = ConceptoCatalogo::withTrashed()->findOrFail($request->id);

           // Verifica si el registro está eliminado
        if ($conceptoCatalogo->trashed()) {
            // Restaura el registro
            $conceptoCatalogo->restore();
            return response()->json(['message' => 'El concepto ha sido restaurado.'], 200);
        }

    }

}

