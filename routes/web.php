<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('landing');
})->name("landing");

Route::get('/resumirPdf', function () {
    return view('ResumidorPdfs');
})->name("resumirPdf");

Route::get('/gestorRecursos', function () {
    return view('gestorRecursos');
})->name("gestorRecursos");