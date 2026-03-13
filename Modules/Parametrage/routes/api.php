<?php

use App\Http\Controllers\TenantFileController;
use Illuminate\Support\Facades\Route;
use Modules\Parametrage\Http\Controllers\Api\V1\AnneeAcademiqueController;
use Modules\Parametrage\Http\Controllers\Api\V1\BaremeMentionController;
use Modules\Parametrage\Http\Controllers\Api\V1\PortailActualiteController;
use Modules\Parametrage\Http\Controllers\Api\V1\PortailConfigController;
use Modules\Parametrage\Http\Controllers\Api\V1\PortailContactController;
use Modules\Parametrage\Http\Controllers\Api\V1\PortailGalerieController;
use Modules\Parametrage\Http\Controllers\Api\V1\PortailHeroController;
use Modules\Parametrage\Http\Controllers\Api\V1\PortailMenuItemController;
use Modules\Parametrage\Http\Controllers\Api\V1\PortailSectionController;
use Modules\Parametrage\Http\Controllers\Api\V1\PortailStatsHeroController;

Route::prefix('v1')->middleware(['auth:api', 'verified'])->prefix('parametrage')->group(function () {
    Route::apiResource('annee-academique', AnneeAcademiqueController::class)
        ->parameters(['annee-academique' => 'annee_academique'])
        ->names('parametrage.annee-academique');
    Route::apiResource('bareme-mention', BaremeMentionController::class)
        ->parameters(['bareme-mention' => 'bareme_mention'])
        ->names('parametrage.bareme-mention');

    // Portail — file URL resolver (path stored as tenant_bucket/...)
    Route::get('portail/file', [TenantFileController::class, 'show'])->name('parametrage.portail.file');

    // Portail — singletons (show + update only)
    Route::get('portail/config', [PortailConfigController::class, 'show'])->name('parametrage.portail.config.show');
    Route::put('portail/config', [PortailConfigController::class, 'update'])->name('parametrage.portail.config.update');
    Route::get('portail/hero', [PortailHeroController::class, 'show'])->name('parametrage.portail.hero.show');
    Route::put('portail/hero', [PortailHeroController::class, 'update'])->name('parametrage.portail.hero.update');
    Route::get('portail/contact', [PortailContactController::class, 'show'])->name('parametrage.portail.contact.show');
    Route::put('portail/contact', [PortailContactController::class, 'update'])->name('parametrage.portail.contact.update');

    // Portail — ordered lists (CRUD + reorder)
    Route::apiResource('portail-menu-items', PortailMenuItemController::class)
        ->parameters(['portail-menu-items' => 'portail_menu_item'])
        ->names('parametrage.portail-menu-items');
    Route::post('portail-menu-items/reorder', [PortailMenuItemController::class, 'reorder'])->name('parametrage.portail-menu-items.reorder');

    Route::apiResource('portail-stats-hero', PortailStatsHeroController::class)
        ->parameters(['portail-stats-hero' => 'portail_stats_hero'])
        ->names('parametrage.portail-stats-hero');
    Route::post('portail-stats-hero/reorder', [PortailStatsHeroController::class, 'reorder'])->name('parametrage.portail-stats-hero.reorder');

    Route::apiResource('portail-galerie', PortailGalerieController::class)
        ->parameters(['portail-galerie' => 'portail_galerie'])
        ->names('parametrage.portail-galerie');
    Route::post('portail-galerie/reorder', [PortailGalerieController::class, 'reorder'])->name('parametrage.portail-galerie.reorder');

    Route::apiResource('portail-sections', PortailSectionController::class)
        ->parameters(['portail-sections' => 'portail_section'])
        ->names('parametrage.portail-sections');
    Route::post('portail-sections/reorder', [PortailSectionController::class, 'reorder'])->name('parametrage.portail-sections.reorder');

    // Portail — actualites (CRUD + epingler + cibler)
    Route::apiResource('portail-actualites', PortailActualiteController::class)
        ->parameters(['portail-actualites' => 'portail_actualite'])
        ->names('parametrage.portail-actualites');
    Route::post('portail-actualites/{portail_actualite}/epingler', [PortailActualiteController::class, 'toggleEpingle'])->name('parametrage.portail-actualites.epingler');
    Route::put('portail-actualites/{portail_actualite}/ciblage', [PortailActualiteController::class, 'updateCiblage'])->name('parametrage.portail-actualites.ciblage');
});
