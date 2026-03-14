<?php

use App\Http\Controllers\LectorPdfController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/enviarPdf', [LectorPdfController::class, 'lectorPdf']);
Route::get("obtenerRespuestas/{id}",[LectorPdfController::class, "obtenerRespuesta"]);
