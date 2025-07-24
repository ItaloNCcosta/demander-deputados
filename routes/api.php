<?php

use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json(['ping' => 'pong']);
})->middleware('throttle:10,1');