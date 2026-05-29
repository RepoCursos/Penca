<?php

use App\Http\Controllers\PencaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PencaController::class, 'index'])->name('penca.index');
Route::post('/matches', [PencaController::class, 'storeMatch'])->name('penca.storeMatch');
Route::post('/predictions', [PencaController::class, 'storePrediction'])->name('penca.storePrediction');
Route::post('/matches/{match}/result', [PencaController::class, 'storeResult'])->name('penca.storeResult');
Route::get('/matches/{match}', [PencaController::class, 'getMatch'])->name('penca.getMatch');
