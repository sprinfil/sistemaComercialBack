<?php

use App\Http\Controllers\Api\ContratoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/generar-contrato', [ContratoController::class, 'generarContratoPdf']);
Route::get('/generar-constancia', [ContratoController::class, 'generarConstanciaFactibilidadPdf']);