<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMultaRequest;
use App\Http\Requests\UpdateMultaRequest;
use App\Models\Multa;
use App\Services\AtencionUsuarios\MultaService;
use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;

class MultaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     protected $multa;

     /**
      * Constructor del controller
      */
     public function __construct(MultaService $_multa)
     {
         $this->multa = $_multa;
     }
    public function index()
    {
        try {
            $multa = (new MultaService())->index();
            return $multa;
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'No se encontraron multas. ' .$ex->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMultaRequest $request )
    {
        try{
            $data = $request->validated();
            $codigo_toma = $request->input('codigo_toma');
            DB::beginTransaction();
            $multacatalogo = $this->multa->store($data, $codigo_toma);
            DB::commit();
            return $multacatalogo;
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se pudo registrar la multa. ' .$e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $multacatalogo = (new MultaService())->show($id);
            return $multacatalogo;
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'No se encontraron multas. ' .$ex->getMessage()
            ], 500);
        }
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMultaRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function generarFormatoMulta()
    {
        try {
            $data = [
                'folio' => '12157',
                'fecha' => 'today'
            ];
            $pdf = FacadePDF::loadView('multas', $data) // Nombre de la vista
            ->setPaper('A4', 'portrait')  // Tamaño de papel y orientación vertical
            ->setOption('margin-top', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0);
            return $pdf->download('multas.pdf');
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se pudo generar el formato. ' .$ex
            ]);
        }
    }
    public function consultar (Request $request)
    {
        try {
            $modelo_multado = $request->input('modelo_multado');
            $id_multado = $request->input('id_multado');
            $id_catalogo_multa = $request->input('id_catalogo_multa');
            $codigo_usuario = $request->input('codigo_usuario');
            $multadueno = (new MultaService())->consultarmulta($modelo_multado, $id_multado, $id_catalogo_multa , $codigo_usuario);
            return $multadueno;
        } catch (Exception $ex) {
           return response()->json([
            'error' => 'Ocurrio un error al consultar las multas. ' .$ex
           ], 500);
        }
    }   
}
