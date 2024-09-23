<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSecuenciaRequest;
use App\Models\Secuencia;
use Illuminate\Http\Request;
use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\FuncCall;

class SecuenciaController extends Controller
{
    public function index(){
        return Secuencia::with('ordenesSecuencia')->take(100)->get();
    }
    public function store(StoreSecuenciaRequest $request){
        $data=$request->validated();
        return response()->json([$data],200) ;
    }
    
}
