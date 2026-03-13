<?php

use Illuminate\Support\Facades\Route;
use Modules\Statistique\Http\Controllers\Api\V1\StatistiqueController;

Route::prefix('v1')->group(function () {
    Route::middleware(['auth:api', 'verified'])->prefix('statistique')->group(function () {
        Route::post('query', [StatistiqueController::class, 'query'])->name('statistique.query');
        Route::delete('cache', [StatistiqueController::class, 'clearCache'])->name('statistique.cache.clear');
    });
});
