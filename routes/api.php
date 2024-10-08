<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('api.v1.articles.show');
Route::get('/articles', [ArticleController::class, 'index'])->name('api.v1.articles.index');


// Route::apiResource('articles', ArticleController::class)->parameters(['articles' => 'article'])->except(['index']);
