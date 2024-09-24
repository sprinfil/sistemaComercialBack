<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCajaCatalogoRequest;
use App\Http\Requests\StoreCajasRequest;
use App\Http\Requests\StoreOperadorAsignadoRequest;
use App\Http\Requests\StoreRetiroCajaRequest;
use App\Http\Requests\StoreSolicitudCancelacionRequest;
use App\Http\Requests\UpdateCajaCatalogoRequest;
use App\Http\Requests\UpdateCajasRequest;
use App\Http\Resources\CajaResource;
use App\Http\Resources\PagoResource;
use App\Http\Resources\SolicitudCancelacionPagoResource;
use App\Models\Caja;
use App\Models\CajaCatalogo;
use App\Models\OperadorAsignado;
use App\Services\Caja\CajaService;
use Database\Seeders\CajaSeeder;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajasController extends Controller
{
    protected $cajaService;

    /**
     * Constructor del controller
     */
    public function __construct(CajaService $_cajaService)
    {
        $this->cajaService = $_cajaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*
        try{
            return response(CajaResource::collection(
                Caja::all()
            ),200);
        } catch(ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No fue posible consultar el pago'
            ], 500);
        }
            */
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCajasRequest $request)
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();
            $apertura = (new CajaService())->iniciarCaja($data);
            DB::commit();
            return $apertura;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al iniciar la caja.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Caja $cajas) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCajasRequest $request)
    {
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $corte = (new CajaService())->corteCaja($data);
            DB::commit();
            return $corte;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al realizar el cierre de caja.'
            ], 500);
        }
    }

    public function asignarOperador(StoreOperadorAsignadoRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $operadorAsignado = (new CajaService())->asignarOperadorService($data['operadores_asignados']);
            DB::commit();
            return $operadorAsignado;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la asignacion del operador.'
            ], 500);
        }
    }

    public function retirarAsignacion(StoreOperadorAsignadoRequest $request)
    {
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $asignacionRetirada = (new CajaService())->retirarAsignacionService($data);
            DB::commit();
            return $asignacionRetirada;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la asignacion del operador.'
            ], 500);
        }
    }

    public function consultarCajas()
    {
        try {
            DB::beginTransaction();
            $cajas = (new CajaService())->consultarCajasCatalogo();
            DB::commit();
            return $cajas;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la busqueda de cajas.'
            ], 500);
        }
    }

    public function guardarCajaCatalogo(StoreCajaCatalogoRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        $cajaCatalogo = (new CajaService())->guardarCajaCatalogoService($data);
        DB::commit();
        return $cajaCatalogo;
        try {
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante el registro de la caja.'
            ], 500);
        }
    }

    public function eliminarCajaCatalogo(string $id)
    {
        try {
            DB::beginTransaction();
            $caja = (new CajaService())->eliminarCajaCatalogoService($id);
            DB::commit();
            return $caja;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante el registro de la caja.'
            ], 500);
        }
    }

    public function restaurarCajaCatalogo(string $id)
    {
        try {
            DB::beginTransaction();
            $caja = (new CajaService())->restaurarCajaCatalogoService($id);
            DB::commit();
            return $caja;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la restauracion de la caja.'
            ], 500);
        }
    }

    public function modificarCajaCatalogo(UpdateCajaCatalogoRequest $request, string $id)
    {

        try {
            $data = $request->validated();
            DB::beginTransaction();
            $caja = (new CajaService())->modificarCajaCatalogoService($data, $id);
            DB::commit();
            return $caja;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la modificacion de la caja.'
            ], 500);
        }
    }

    public function mostrarCaja(string $id)
    {
        try {
            DB::beginTransaction();
            $caja = (new CajaService())->mostrarCajaService($id);
            DB::commit();
            return $caja;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la busqueda de la caja.'
            ], 500);
        }
    }

    public function buscarSesionCaja(Request $request)
    {
        try {
            DB::beginTransaction();
            $cajaSesion = (new CajaService())->buscarSesionCajaService($request);
            DB::commit();
            return  $cajaSesion;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se encontro sesion activa de la caja asociada a este operador.'
            ], 500);
        }
    }

    //RetiroMetodos
    public function registrarRetiro(StoreRetiroCajaRequest $request)
    {
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $retiro = (new CajaService())->registrarRetiroService($data);
            Db::commit();
            return $retiro;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'Ocurrio un error durante el registro del retiro.' . $ex
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Caja $cajas) {}

    /**
     * Display a listing of the resource.
     */
    public function cargosPorModelo(Request $request) {}

    public function test()
    {
        $pagos = Caja::find(1)->pagosPorTipo('efectivo');
    }

    public function cargosPorCaja(Request $request)
    {
        try {
            return response($this->cajaService->cargoPorCaja($request));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudieron consultar los cargos de la caja'
            ], 500);
        }
    }

    public function pagosPorCaja(Request $request)
    {
        try {
            return response(
                PagoResource::collection(
                    $this->cajaService->pagosPorCaja($request)
                )
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo consultar los pagos de esta caja'
            ], 500);
        }
    }

    public function solicitarCancelacionPago(StoreSolicitudCancelacionRequest $request)
    {
        try {
            return response()->json(new SolicitudCancelacionPagoResource($this->cajaService->solicitudCancelacionPago($request)), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo solicitar la cancelacion del pago'
            ], 500);
        }
    }

    public function estadoSesionCobro(Request $data)
    {
        try {
            DB::beginTransaction();
            $cajaSesion = (new CajaService())->estadoSesionCobroService($data);
            DB::commit();
            return $cajaSesion;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la busqueda de la sesion'
            ], 500);
        }
    }

    public function sesionPrevia()
    {
        try {
            DB::beginTransaction();
            $cajaSesionesPre = (new CajaService())->sesionPreviaService();
            DB::commit();
            return $cajaSesionesPre;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la busqueda de la sesion'
            ], 500);
        }
    }

    public function cortesRechazados()
    {
        try {
            DB::beginTransaction();
            $cortes = (new CajaService())->cortesRechazadosService();
            DB::commit();
            return $cortes;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la busqueda de los cortes'
            ], 500);
        }
    }

    public function solicitudesCancelacionPago(Request $request)
    {
        try {
            return response()->json(SolicitudCancelacionPagoResource::collection($this->cajaService->solicitudesCancelacion($request)), 200);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la busqueda de las solicitudes' . $ex
            ], 500);
        }
    }

    public function actualizarSolicitudCancelacionPago(Request $request)
    { //actualizarSolicitudCancelacion
        try {
            return response()->json($this->cajaService->actualizarSolicitudCancelacion($request), 200);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrió un error durante la actualización de la solicitud: ' . $ex->getMessage()
            ], 500);
        }
    }

    public function cargarLetra($id)
    {
        try {
            return response()->json($this->cajaService->cargarLetra($id), 200);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se pudo cargar la letra: ' . $ex->getMessage()
            ], 500);
        }
    }

    public function cargarLetras(Request $request)
    {
        try {
            // Obtenemos el array de IDs de letras desde el request
            // Aquí asumimos que el JSON contiene un campo 'ids' con un array de IDs
            $ids = $request->input('ids');

            // Llamamos al método del servicio pasando el array de IDs
            return response()->json($this->cajaService->cargarLetras($ids), 200);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se pudo cargar la letra: ' . $ex->getMessage()
            ], 500);
        }
    }

}
