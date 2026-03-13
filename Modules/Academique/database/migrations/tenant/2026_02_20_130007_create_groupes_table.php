<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groupes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('niveau_id')->constrained('niveaux')->cascadeOnDelete();
            $table->uuid('annee_academique_id')->index();
            $table->string('code', 50);
            $table->string('libelle', 255);
            $table->string('type', 20); // classe / td / tp
            $table->unsignedInteger('effectif_max')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groupes');
    }
};
