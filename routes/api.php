<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArticleController;

Route::apiResource('users', UserController::class);
Route::apiResource('articles', ArticleController::class);