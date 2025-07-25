<?php

use App\Http\Controllers\Deputy\DeputyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [DeputyController::class, 'index'])->name('deputy.index');
Route::get('/deputy/{deputy}/expenses', [DeputyController::class, 'show'])->name('deputy.show');
