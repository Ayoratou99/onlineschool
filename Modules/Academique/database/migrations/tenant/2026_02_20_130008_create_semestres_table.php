<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semestres', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('niveau_id')->constrained('niveaux')->cascadeOnDelete();
            $table->uuid('annee_academique_id')->index();
            $table->string('code', 20);
            $table->string('libelle', 255);
            $table->string('type', 20); // semestre / trimestre / annuel
            $table->unsignedInteger('ordre')->default(0);
            $table->date('date_debut');
            $table->date('date_fin');
            $table->date('date_debut_examen')->nullable();
            $table->date('date_fin_examen')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semestres');
    }
};
