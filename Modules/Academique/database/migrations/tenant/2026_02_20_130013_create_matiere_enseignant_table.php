<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matiere_enseignant', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('matiere_id')->constrained('matieres')->cascadeOnDelete();
            $table->uuid('enseignant_id')->index();
            $table->uuid('annee_academique_id')->index();
            $table->foreignUuid('groupe_id')->nullable()->constrained('groupes')->nullOnDelete();
            $table->string('type_seance', 20); // cm / td / tp / cours_integre
            $table->boolean('is_principal')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matiere_enseignant');
    }
};
