<?php

use App\Http\Controllers\Api\ArticleController;
use Illuminate\Support\Facades\Route;

Route::apiResources([
    'articles' => ArticleController::class,
]);

Route::delete('/articles', [ArticleController::class, 'destroy']);
