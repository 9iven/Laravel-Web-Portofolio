<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TechnologyController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:portfolio-api')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{project:slug}', [ProjectController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/technologies', [TechnologyController::class, 'index']);
});
