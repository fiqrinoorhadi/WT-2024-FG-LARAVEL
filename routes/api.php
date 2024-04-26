<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/v1/auth/register',[AuthenticationController::class, 'register']);
Route::post('/v1/auth/login',[AuthenticationController::class, 'login']);
Route::post('/v1/auth/logout',[AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
