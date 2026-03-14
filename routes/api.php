<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\LectorPdfController;
use App\Http\Controllers\RecursosServiciosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//enlace para enviar el pdf y que el modelo pueda resumirlo
Route::post('/enviarPdf', [LectorPdfController::class, 'lectorPdf']);

//enlace para obtener los recursos del sistema
Route::get("/obtenerRecursos", [RecursosServiciosController::class, "obtenerRecursos"]);
route::get("/estadoPc",[RecursosServiciosController::class,"obtenerEstado"]);
Route::get("/analizarSistema",[RecursosServiciosController::class,"analizarSistema"]);

//este es un enlace de pruebas
Route::get("obtenerRespuestas/{id}",[LectorPdfController::class, "obtenerRespuesta"]);

//chatBot
Route::get("chatSistema",[ChatController::class, "chatSistema"]);