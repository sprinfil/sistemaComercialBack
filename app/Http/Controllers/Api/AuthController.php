<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
    
        $credentials = $request->validated();

        if(!Auth::attempt($credentials)){
            return response([
                "message" => "tus credenciales no son correctas",
            ], 422);
        }

        $user = Auth::user();

        if ($user) {
            Log::info('User authenticated: ' . $user->email);
        } else {
            Log::info('User not authenticated');
        }

        
        $token = $user->createToken("main")->plainTextToken;

        return response(compact("user","token"));
    }
    public function logout(Request $request){
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response("", 204);
    }
}
