<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PostController as V1PostController;
use App\Http\Controllers\Api\V2\PostController as V2PostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

 
Route::prefix('v1')->group(function () {
    Route::apiResource('posts', V1PostController::class);
});     
Route::prefix('v2')->group(function () {
    Route::apiResource('posts', V2PostController::class);
});     