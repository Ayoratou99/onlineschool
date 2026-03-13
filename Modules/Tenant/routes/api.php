<?php

use Illuminate\Support\Facades\Route;
use Modules\Tenant\Http\Controllers\Api\V1\AdminAuthController;
use Modules\Tenant\Http\Controllers\Api\V1\TenantController;

// Central admin auth: login is public; refresh/logout/me require admin token
Route::prefix('v1')->group(function () {
    Route::post('admin/login', [AdminAuthController::class, 'login'])->name('tenant.admin.login');
});

Route::prefix('v1')->middleware(['auth:admin'])->group(function () {
    Route::post('admin/refresh', [AdminAuthController::class, 'refresh'])->name('tenant.admin.refresh');
    Route::post('admin/logout', [AdminAuthController::class, 'logout'])->name('tenant.admin.logout');
    Route::get('admin/me', [AdminAuthController::class, 'me'])->name('tenant.admin.me');
    Route::get('tenant/stats/dashboard', [TenantController::class, 'stats'])->name('tenant.stats');
    Route::apiResource('tenant', TenantController::class)->parameters(['tenant' => 'tenant'])->names('tenant');
    Route::post('tenant/{tenant}/clean', [TenantController::class, 'clean'])->name('tenant.clean');
    Route::post('tenant/{tenant}/lock', [TenantController::class, 'lock'])->name('tenant.lock');
    Route::post('tenant/{tenant}/unlock', [TenantController::class, 'unlock'])->name('tenant.unlock');
});
