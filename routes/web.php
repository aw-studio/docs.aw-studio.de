<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

// Auth

Route::get('logout', [LoginController::class, 'logout']);
Route::get('login/github', [LoginController::class, 'redirectToGithubProvider']);
Route::get('login/github/callback', [LoginController::class, 'handleGithubProviderCallback']);
