<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('niveaux', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('filiere_id')->constrained('filieres')->cascadeOnDelete();
            $table->foreignUuid('parcours_id')->nullable()->constrained('parcours')->nullOnDelete();
            $table->uuid('annee_academique_id')->index();
            $table->string('code', 20);
            $table->string('libelle', 255);
            $table->unsignedInteger('ordre')->default(0);
            $table->unsignedInteger('credits_requis')->nullable();
            $table->unsignedInteger('effectif_max')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('niveaux');
    }
};
