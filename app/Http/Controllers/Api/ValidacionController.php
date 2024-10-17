<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Lectura\ValidacionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidacionController extends Controller
{
    protected $validacion;

    /**
     * Constructor del controller
     */
    public function __construct(ValidacionService $_validacion)
    {
        $this->validacion = $_validacion;
    }

    public function anomaliasperiodo ($id)
    {
        try {
            //$data = $request->validate();
            $anomalias = $this->validacion->anomaliasperiodo($id);
            return $anomalias;
        } catch (Exception $ex) {
           DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al mostrar las anomalias. ' .$ex->getMessage()
            ], 500);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
}
