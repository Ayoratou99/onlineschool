<?php

use Illuminate\Support\Facades\Route;
use Modules\Securite\Http\Controllers\Api\V1\AuthController;
use Modules\Securite\Http\Controllers\Api\V1\PermissionController;
use Modules\Securite\Http\Controllers\Api\V1\RoleController;
use Modules\Securite\Http\Controllers\Api\V1\TwoFactorController;
use Modules\Securite\Http\Controllers\Api\V1\UserController;

Route::prefix('v1')->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login'])->name('securite.auth.login');
        Route::post('confirm-email', [AuthController::class, 'confirmEmail'])->name('securite.auth.confirm-email');
        Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('securite.auth.reset-password');
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('2fa/verify', [AuthController::class, 'verify2fa'])->name('securite.2fa.verify');
        Route::middleware('auth:api')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', function () {
                return response()->json(auth()->user()->load('roles', 'roles.permissions'));
            })->name('securite.me');
            Route::post('2fa/enable', [TwoFactorController::class, 'enable'])->name('securite.2fa.enable');
            Route::post('2fa/confirm', [TwoFactorController::class, 'confirm'])->name('securite.2fa.confirm');
            Route::post('2fa/disable', [TwoFactorController::class, 'disable'])->name('securite.2fa.disable');
        });
    });

    Route::middleware(['auth:api', 'verified'])->prefix('securite')->group(function () {
        // Users
        Route::apiResource('user', UserController::class)->names('securite.user');
        Route::post('user/{user}/assign-role', [UserController::class, 'assignRole'])->name('securite.user.assign-role');
        Route::post('user/{user}/unassign-role', [UserController::class, 'unassignRole'])->name('securite.user.unassign-role');
        Route::post('user/{user}/reset-2fa', [TwoFactorController::class, 'reset'])->name('securite.user.reset-2fa');
        
        // Roles
        Route::apiResource('role', RoleController::class)->names('securite.role');
        Route::post('role/{role}/assign-permission', [RoleController::class, 'assignPermission'])->name('securite.role.assign-permission');
        Route::post('role/{role}/unassign-permission', [RoleController::class, 'unassignPermission'])->name('securite.role.unassign-permission');
        
        // Permissions
        Route::apiResource('permission', PermissionController::class)->names('securite.permission');
    });
});
