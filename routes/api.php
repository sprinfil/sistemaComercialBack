<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// api login
Route::post('/login', [AuthController::class, "login"]);

// apis catalogos
function includeRouteFiles($directory) {
    $routeFiles = glob($directory . '/*.php');
    foreach ($routeFiles as $routeFile) {
        require_once $routeFile;
    }
    $subdirectories = glob($directory . '/*', GLOB_ONLYDIR);
    foreach ($subdirectories as $subdirectory) {
        includeRouteFiles($subdirectory);
    }
}

Route::middleware('auth:sanctum')->group(function () {
    // Incluir todos los archivos de rutas en la carpeta 'catalogo'
    includeRouteFiles(base_path('routes/api'));
});
