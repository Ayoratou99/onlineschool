<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emplois_du_temps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('semestre_id')->constrained('semestres')->cascadeOnDelete();
            $table->foreignUuid('niveau_id')->constrained('niveaux')->cascadeOnDelete();
            $table->foreignUuid('groupe_id')->nullable()->constrained('groupes')->nullOnDelete();
            $table->foreignUuid('matiere_id')->constrained('matieres')->cascadeOnDelete();
            $table->foreignUuid('salle_id')->constrained('salles')->cascadeOnDelete();
            $table->uuid('enseignant_id')->index();
            $table->uuid('annee_academique_id')->index();
            $table->string('type_seance', 20); // cm / td / tp
            $table->string('jour', 20); // lundi / mardi / mercredi / jeudi / vendredi / samedi
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('frequence', 20); // hebdomadaire / bi_mensuel / ponctuel
            $table->date('date_specifique')->nullable();
            $table->date('date_debut_effectif');
            $table->date('date_fin_effectif');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emplois_du_temps');
    }
};
