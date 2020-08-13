<?php

use App\Fjuse;
use App\Http\Controllers\DocsController;
use App\Http\Controllers\LoginController;
use App\Models\Repository;
use App\Models\User;
use App\Services\GitLab\GitLabApi;
use Illuminate\Support\Facades\Route;

if (! defined('DEFAULT_PAGE')) {
    define('DEFAULT_PAGE', 'readme');
}

Route::get('/', fn () => view('welcome'));

// Docs

Route::get('docs/{page?}/{sub_page?}', [DocsController::class, 'show'])->name('docs');

// Auth

Route::get('logout', [LoginController::class, 'logout']);
Route::get('login/github', [LoginController::class, 'redirectToGithubProvider']);
Route::get('login/github/callback', [LoginController::class, 'handleGithubProviderCallback']);
