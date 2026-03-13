<?php

use Illuminate\Support\Facades\Route;
use Modules\Document\Http\Controllers\Api\V1\GeneratedDocumentController;
use Modules\Document\Http\Controllers\Api\V1\TemplateDocumentController;

Route::prefix('v1')->group(function () {
    Route::middleware(['auth:api', 'verified'])->prefix('document')->group(function () {
        Route::apiResource('template-documents', TemplateDocumentController::class)->names('document.template-documents');
        Route::get('generated-documents', [GeneratedDocumentController::class, 'index'])->name('document.generated-documents.index');
        Route::get('generated-documents/{generatedDocument}', [GeneratedDocumentController::class, 'show'])->name('document.generated-documents.show');
    });
});
