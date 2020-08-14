<?php

use App\Http\Controllers\DocsController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

if (! defined('DEFAULT_PAGE')) {
    define('DEFAULT_PAGE', 'readme');
}

Route::get('/', fn () => view('welcome'));

// Auth

Route::get('logout', [LoginController::class, 'logout']);
Route::get('login/github', [LoginController::class, 'redirectToGithubProvider']);
Route::get('login/github/callback', [LoginController::class, 'handleGithubProviderCallback']);

// Docs
if (! Request::is('admin*')) {
    Route::get('/{project}/{version?}/{page?}/{sub_page?}', [DocsController::class, 'show'])->name('docs');
}
