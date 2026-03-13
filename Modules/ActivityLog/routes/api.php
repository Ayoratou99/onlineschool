<?php

use Illuminate\Support\Facades\Route;
use Modules\ActivityLog\Http\Controllers\Api\V1\ActivityLogController;

Route::prefix('v1')->group(function () {
    Route::middleware(['auth:api', 'verified'])->prefix('activitylog')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('activitylog.index');
        Route::get('{activityLog}', [ActivityLogController::class, 'show'])->name('activitylog.show');
    });
});
