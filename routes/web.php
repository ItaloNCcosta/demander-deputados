<?php

use App\Http\Controllers\Deputy\DeputyController;
use App\Http\Controllers\Deputy\DeputyRankingController;
use App\Http\Controllers\Expense\ExpensesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DeputyController::class, 'index'])->name('deputies.index');
Route::get('/deputies/{deputy}/expenses', [DeputyController::class, 'show'])->name('deputies.show');
Route::get('deputies/ranking', [DeputyRankingController::class, 'index'])
->name('deputies.ranking');

Route::get('/expenses', [ExpensesController::class, 'index'])->name('expenses.index');

Route::get('/about', function () {
    return view('about');
})->name('about');