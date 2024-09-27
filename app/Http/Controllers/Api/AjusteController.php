<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompareAjusteRequest;
use App\Models\Ajuste;
use App\Http\Requests\StoreAjusteRequest;
use App\Http\Requests\UpdateAjusteRequest;
use App\Http\Resources\AjusteResource;
use App\Services\AtencionUsuarios\AjusteService;
use Exception;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;

class AjusteController extends Controller
{
    protected $ajusteService;

    /**
     * Constructor del controller
     */
    public function __construct(AjusteService $_ajusteService)
    {
        $this->ajusteService = $_ajusteService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response(AjusteResource::collection(
                $this->ajusteService->consultarAjustes()
            ), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar los ajustes'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function compare(CompareAjusteRequest $request)
    {
        try {
            $data = $request->all();
            return response(
                $this->ajusteService->conceptosAjustables($data),
                200
            );
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar los ajustes'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAjusteRequest $request)
    {
        try {
            $data = $request->all();
            return response(
                new AjusteResource($this->ajusteService->crearAjuste($data)),
                200
            );
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No fue posible ajustar los cargos'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            return response(
                $this->ajusteService->consultarAjuste($id),
                200
            );
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar los ajustes'
            ], 500);
        }
    }

    public function showPorToma(Request $request)
    {
        try {
            $data = $request->all();
            return response(
                $this->ajusteService->consultarAjuste($data),
                200
            );
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar los ajustes'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function cancel(UpdateAjusteRequest $request)
    {
        try {
            $data = $request->all();
            return response(
                new AjusteResource($this->ajusteService->cancelarAjuste($data)),
                200
            );
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No fue posible cancelar el ajuste'
            ], 500);
        }
    }
    public function reportes(Request $request){
        try {
            $data = $request->all();
            $reporte=(new AjusteService())->generarReportes($data);
            //$reporte['casilla']=true;
      //            <input type="checkbox" name="" id="" checked="true"><p>{{ $reporte['casilla'] }}</p>
             //return $reporte['casilla'];
             $fecha=helperFechaAhora();
             $fecha=Carbon::parse($fecha)->format('Y-m-d'); 
            $pdf = FacadePDF::loadView('ajuste',["reporte"=>$reporte, "fecha"=>$fecha])
            ->setPaper('A4', 'portrait') // Tamaño de papel y orientación
            ->setOption('margin-top', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0);
        return $pdf->download('ajuste.pdf');
            //return response()->json(["Reporte"=>$reporte],200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No fue posible generar el reporte para los ajustes: '.$e
            ], 500);
        }
    }
}
