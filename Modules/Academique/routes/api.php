<?php

use Illuminate\Support\Facades\Route;
use Modules\Academique\Http\Controllers\Api\V1\BatimentController;
use Modules\Academique\Http\Controllers\Api\V1\CycleController;
use Modules\Academique\Http\Controllers\Api\V1\DomaineController;
use Modules\Academique\Http\Controllers\Api\V1\EtablissementController;
use Modules\Academique\Http\Controllers\Api\V1\EmploiDuTempsController;
use Modules\Academique\Http\Controllers\Api\V1\EmploiDuTempsExceptionController;
use Modules\Academique\Http\Controllers\Api\V1\EtageController;
use Modules\Academique\Http\Controllers\Api\V1\FiliereController;
use Modules\Academique\Http\Controllers\Api\V1\GroupeController;
use Modules\Academique\Http\Controllers\Api\V1\MatiereController;
use Modules\Academique\Http\Controllers\Api\V1\MatiereEnseignantController;
use Modules\Academique\Http\Controllers\Api\V1\NiveauController;
use Modules\Academique\Http\Controllers\Api\V1\ParcoursController;
use Modules\Academique\Http\Controllers\Api\V1\ProgrammeController;
use Modules\Academique\Http\Controllers\Api\V1\ProgrammeDetailController;
use Modules\Academique\Http\Controllers\Api\V1\SalleController;
use Modules\Academique\Http\Controllers\Api\V1\SalleIndisponibiliteController;
use Modules\Academique\Http\Controllers\Api\V1\SemestreController;
use Modules\Academique\Http\Controllers\Api\V1\UniteEnseignementController;

Route::prefix('v1')->middleware(['auth:api', 'verified'])->prefix('academique')->group(function () {
    Route::apiResource('cycles', CycleController::class)->parameters(['cycles' => 'cycle'])->names('academique.cycles');
    Route::apiResource('domaines', DomaineController::class)->parameters(['domaines' => 'domaine'])->names('academique.domaines');
    Route::apiResource('etablissements', EtablissementController::class)->parameters(['etablissements' => 'etablissement'])->names('academique.etablissements');
    Route::apiResource('filieres', FiliereController::class)->parameters(['filieres' => 'filiere'])->names('academique.filieres');
    Route::apiResource('parcours', ParcoursController::class)->names('academique.parcours');
    Route::apiResource('niveaux', NiveauController::class)->parameters(['niveaux' => 'niveau'])->names('academique.niveaux');
    Route::apiResource('groupes', GroupeController::class)->parameters(['groupes' => 'groupe'])->names('academique.groupes');
    Route::apiResource('semestres', SemestreController::class)->parameters(['semestres' => 'semestre'])->names('academique.semestres');
    Route::apiResource('unites-enseignement', UniteEnseignementController::class)->parameters(['unites-enseignement' => 'unite_enseignement'])->names('academique.unites-enseignement');
    Route::apiResource('matieres', MatiereController::class)->parameters(['matieres' => 'matiere'])->names('academique.matieres');
    Route::apiResource('programmes', ProgrammeController::class)->parameters(['programmes' => 'programme'])->names('academique.programmes');
    Route::apiResource('programme-details', ProgrammeDetailController::class)->parameters(['programme-details' => 'programme_detail'])->names('academique.programme-details');
    Route::apiResource('matiere-enseignants', MatiereEnseignantController::class)->parameters(['matiere-enseignants' => 'matiere_enseignant'])->names('academique.matiere-enseignants');
    Route::apiResource('batiments', BatimentController::class)->parameters(['batiments' => 'batiment'])->names('academique.batiments');
    Route::apiResource('etages', EtageController::class)->parameters(['etages' => 'etage'])->names('academique.etages');
    Route::apiResource('salles', SalleController::class)->parameters(['salles' => 'salle'])->names('academique.salles');
    Route::apiResource('salle-indisponibilites', SalleIndisponibiliteController::class)->parameters(['salle-indisponibilites' => 'salle_indisponibilite'])->names('academique.salle-indisponibilites');
    Route::apiResource('emplois-du-temps', EmploiDuTempsController::class)->parameters(['emplois-du-temps' => 'emploi_du_temps'])->names('academique.emplois-du-temps');
    Route::apiResource('emploi-du-temps-exceptions', EmploiDuTempsExceptionController::class)->parameters(['emploi-du-temps-exceptions' => 'emploi_du_temps_exception'])->names('academique.emploi-du-temps-exceptions');
});
