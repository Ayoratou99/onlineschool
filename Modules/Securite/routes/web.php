<?php

use Illuminate\Support\Facades\Route;
use Modules\Securite\Http\Controllers\SecuriteController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('securites', SecuriteController::class)->names('securite');
});
