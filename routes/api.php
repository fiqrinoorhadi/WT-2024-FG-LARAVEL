<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\AuthenticationController;


Route::post('/v1/auth/register',[AuthenticationController::class, 'register']);
Route::post('/v1/auth/login',[AuthenticationController::class, 'login']);
Route::post('/v1/auth/logout',[AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);

Route::post('/v1/posts',[PostController::class, 'store'])->middleware(['auth:sanctum']);
Route::delete('/v1/posts/:id',[PostController::class, 'destroy'])->middleware(['auth:sanctum', 'pemilik-postingan']);
Route::get('/v1/posts',[PostController::class, 'index'])->middleware(['auth:sanctum']);

Route::post('/v1/users/{username}/follow',[FollowController::class, 'follow'])->middleware(['auth:sanctum']);
Route::delete('/v1/users/{username}/unfollow',[FollowController::class, 'unfollow'])->middleware(['auth:sanctum']);
Route::get('/v1/users/following',[FollowController::class, 'following'])->middleware(['auth:sanctum']);
Route::put('/v1/users/{username}/accept',[FollowController::class, 'accept'])->middleware(['auth:sanctum']);
Route::get('/v1/users/{username}/followers',[FollowController::class, 'followers'])->middleware(['auth:sanctum']);

Route::get('/v1/users',[UserController::class, 'users'])->middleware(['auth:sanctum']);
Route::get('/v1/users/{username}',[UserController::class, 'userDetail'])->middleware(['auth:sanctum']);



