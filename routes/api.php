<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ArticleController;

Route::apiResources([
    'articles' => ArticleController::class,
]);


Route::delete('/articles', [ArticleController::class, 'destroy']);
