<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run on tenant DB only. */
    public function up(): void
    {
        Schema::create('regles_validation_ue', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ue_id')->index();         // Externe → Academique, pas de FK
            $table->foreignUuid('annee_academique_id')->constrained('annees_academiques')->cascadeOnDelete();
            $table->string('type_regle', 50)->nullable();
            $table->json('config')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regles_validation_ue');
    }
};
