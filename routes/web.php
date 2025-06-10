<?php

use App\Http\Controllers\RenderController;
use App\Http\Controllers\ZplRenderController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [RenderController::class, 'index'])->name('home');   // página principal
Route::post('/render-zpl', ZplRenderController::class)->name('render-zpl');