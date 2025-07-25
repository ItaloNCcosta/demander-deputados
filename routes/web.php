<?php

use App\Http\Controllers\Deputy\DeputyController;
use App\Http\Controllers\Deputy\DeputyRankingController;
use App\Http\Controllers\Expense\ExpensesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [DeputyController::class, 'index'])->name('deputy.index');
Route::get('/deputy/{deputy}/expenses', [DeputyController::class, 'show'])->name('deputy.show');
Route::get('deputies/ranking', [DeputyRankingController::class, 'index'])
    ->name('deputies.ranking');

Route::get('/expenses', [ExpensesController::class, 'index'])->name('expenses.index');
